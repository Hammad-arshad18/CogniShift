<?php

namespace App\Models;

use App\Enums\PayrollStatus;
use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'pay_period_start',
        'pay_period_end',
        'base_salary',
        'overtime_pay',
        'deductions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'pay_period_start' => 'date',
            'pay_period_end' => 'date',
            'base_salary' => 'decimal:2',
            'overtime_pay' => 'decimal:2',
            'deductions' => 'decimal:2',
            'status' => PayrollStatus::class,
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
