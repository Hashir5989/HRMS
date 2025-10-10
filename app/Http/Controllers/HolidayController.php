<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date')->get()->groupBy(fn($h) => \Carbon\Carbon::parse($h->date)->format('Y'));
        $upcomingHolidays = Holiday::where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('holidays.index', compact('holidays', 'upcomingHolidays'));
    }

    public function create()
    {
        $this->authorize('department.manage');
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $this->authorize('department.manage');

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date',
            'type'         => 'required|in:public,company,optional',
            'description'  => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->boolean('is_recurring');
        Holiday::create($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday added successfully.');
    }

    public function edit(Holiday $holiday)
    {
        $this->authorize('department.manage');
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $this->authorize('department.manage');

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date',
            'type'         => 'required|in:public,company,optional',
            'description'  => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->boolean('is_recurring');
        $holiday->update($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $this->authorize('department.manage');
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
