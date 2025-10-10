<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['Super Admin', 'Admin', 'HR'])) {
            $leaves = LeaveApplication::with(['user', 'leaveType', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $leaves = LeaveApplication::with(['leaveType', 'approver'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        $stats = [
            'pending' => LeaveApplication::where('status', 'pending')->count(),
            'approved' => LeaveApplication::where('status', 'approved')->count(),
            'rejected' => LeaveApplication::where('status', 'rejected')->count(),
        ];

        return view('leaves.index', compact('leaves', 'stats'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        return view('leaves.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $totalDays = $start->diffInDays($end) + 1;

        LeaveApplication::create([
            'user_id' => auth()->id(),
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    public function show(LeaveApplication $leaf)
    {
        $leaf->load(['user', 'leaveType', 'approver']);
        return view('leaves.show', ['leave' => $leaf]);
    }

    public function update(Request $request, LeaveApplication $leaf)
    {
        $this->authorize('leave.approve');

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        $leaf->update([
            'status' => $validated['status'],
            'admin_remarks' => $validated['admin_remarks'] ?? null,
            'approved_by' => auth()->id(),
            'responded_at' => now(),
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave application ' . $validated['status'] . ' successfully.');
    }

    public function destroy(LeaveApplication $leaf)
    {
        if ($leaf->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'HR'])) {
            abort(403);
        }

        if ($leaf->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        $leaf->update(['status' => 'cancelled']);
        return redirect()->route('leaves.index')->with('success', 'Leave application cancelled.');
    }
}
