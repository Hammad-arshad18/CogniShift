<?php

namespace App\Jobs;

use App\Enums\PayrollStatus;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\PayrollRecord;
use App\Models\PayrollRule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayrollBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenantId;
    public $startDate;
    public $endDate;

    public function __construct($tenantId, $startDate, $endDate)
    {
        $this->tenantId = $tenantId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle(): void
    {
        // Setup Tenant Scope context manually if running in queue
        session()->put('tenant_id', $this->tenantId);

        $rules = PayrollRule::where('tenant_id', $this->tenantId)->first();
        if (!$rules) {
            // Fallback rules
            $rules = new PayrollRule([
                'standard_hours_per_week' => 40,
                'overtime_multiplier' => 1.5,
                'tax_percentage' => 10.0,
            ]);
        }

        $employees = Employee::where('tenant_id', $this->tenantId)->get();

        foreach ($employees as $employee) {
            // Get all attendances for this period
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$this->startDate, $this->endDate])
                ->whereNotNull('clock_in')
                ->whereNotNull('clock_out')
                ->get();

            $totalHours = 0;
            foreach ($attendances as $attendance) {
                $clockIn = Carbon::parse($attendance->clock_in);
                $clockOut = Carbon::parse($attendance->clock_out);
                $totalHours += $clockOut->diffInHours($clockIn);
            }

            // Assume base salary is $20/hr for this example (in a real app, this would be on the Employee or Role model)
            $hourlyRate = 20.00; 

            // Calculate standard vs overtime
            $standardHours = min($totalHours, $rules->standard_hours_per_week);
            $overtimeHours = max(0, $totalHours - $rules->standard_hours_per_week);

            $basePay = $standardHours * $hourlyRate;
            $overtimePay = $overtimeHours * $hourlyRate * $rules->overtime_multiplier;

            $grossPay = $basePay + $overtimePay;
            $deductions = $grossPay * ($rules->tax_percentage / 100);

            PayrollRecord::updateOrCreate(
                [
                    'tenant_id' => $this->tenantId,
                    'employee_id' => $employee->id,
                    'pay_period_start' => $this->startDate,
                    'pay_period_end' => $this->endDate,
                ],
                [
                    'base_salary' => $basePay,
                    'overtime_pay' => $overtimePay,
                    'deductions' => $deductions,
                    'status' => PayrollStatus::DRAFT,
                ]
            );
        }
    }
}
