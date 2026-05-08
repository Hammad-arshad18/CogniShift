<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'geolocation_data',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'geolocation_data' => 'array',
            'status' => AttendanceStatus::class,
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
