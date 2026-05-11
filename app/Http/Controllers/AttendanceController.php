<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index()
    {
        return Inertia::render('Attendances/Index', [
            'attendances' => Attendance::with(['employee.user'])->latest()->get(),
            'employees' => Employee::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date',
            'clock_out' => 'nullable|date',
            'status' => 'required|string',
        ]);

        Attendance::create($validated);

        return redirect()->back()->with('message', 'Attendance logged successfully.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in' => 'nullable|date',
            'clock_out' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $attendance->update($validated);

        return redirect()->back()->with('message', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('message', 'Attendance deleted successfully.');
    }
}
