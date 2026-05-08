<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'department_id',
        'role_id',
        'date_of_joining',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_joining' => 'date',
            'status' => EmployeeStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrollRecords()
    {
        return $this->hasMany(PayrollRecord::class);
    }
}
