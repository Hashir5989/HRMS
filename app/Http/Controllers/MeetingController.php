<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $meetings = Meeting::with(['creator', 'attendees'])
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $user->id));
            })
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        $upcomingMeetings = Meeting::with(['creator'])
            ->where('start_time', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('meetings.index', compact('meetings', 'upcomingMeetings'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('meetings.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_time'   => 'required|date|after:now',
            'end_time'     => 'required|date|after:start_time',
            'location'     => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'attendees'    => 'nullable|array',
            'attendees.*'  => 'exists:users,id',
        ]);

        $meeting = Meeting::create([
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'start_time'   => $validated['start_time'],
            'end_time'     => $validated['end_time'],
            'location'     => $validated['location'] ?? null,
            'meeting_link' => $validated['meeting_link'] ?? null,
            'created_by'   => auth()->id(),
            'status'       => 'scheduled',
        ]);

        if (!empty($validated['attendees'])) {
            $meeting->attendees()->attach($validated['attendees'], ['rsvp' => 'pending']);
        }

        // Always add creator as attendee
        if (!in_array(auth()->id(), $validated['attendees'] ?? [])) {
            $meeting->attendees()->attach(auth()->id(), ['rsvp' => 'accepted']);
        }

        return redirect()->route('meetings.index')->with('success', 'Meeting scheduled successfully.');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['creator', 'attendees']);
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $this->authorize('update', $meeting);
        $users = User::orderBy('name')->get();
        return view('meetings.edit', compact('meeting', 'users'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_time'   => 'required|date',
            'end_time'     => 'required|date|after:start_time',
            'location'     => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'status'       => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $meeting->update($validated);
        return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', 'Meeting cancelled.');
    }
}
