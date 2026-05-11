<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index()
    {
        return Inertia::render('Employees/Index', [
            'employees' => Employee::with(['user', 'department', 'role'])->latest()->get(),
            'departments' => Department::all(),
            'roles' => Role::all(),
            'users' => \App\Models\User::whereNotIn('id', Employee::pluck('user_id'))->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'nullable|exists:roles,id',
            'date_of_joining' => 'required|date',
            'status' => 'required|string',
        ]);

        Employee::create($validated);

        return redirect()->back()->with('message', 'Employee created successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|string',
        ]);

        $employee->update($validated);

        return redirect()->back()->with('message', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->back()->with('message', 'Employee deleted successfully.');
    }
}
