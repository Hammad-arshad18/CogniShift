<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayrollController extends Controller
{
    public function index()
    {
        return Inertia::render('Payroll/Index', [
            'records' => PayrollRecord::with(['employee.user'])->latest()->get(),
            'employees' => Employee::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'base_salary' => 'required|numeric',
            'overtime_pay' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        PayrollRecord::create($validated);

        return redirect()->back()->with('message', 'Payroll record created successfully.');
    }

    public function update(Request $request, PayrollRecord $payroll)
    {
        $validated = $request->validate([
            'base_salary' => 'required|numeric',
            'overtime_pay' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        $payroll->update($validated);

        return redirect()->back()->with('message', 'Payroll record updated successfully.');
    }

    public function destroy(PayrollRecord $payroll)
    {
        $payroll->delete();
        return redirect()->back()->with('message', 'Payroll record deleted successfully.');
    }
}
