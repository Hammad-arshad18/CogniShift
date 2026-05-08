<?php

namespace App\Models;

use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class PayrollRule extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_id',
        'standard_hours_per_week',
        'overtime_multiplier',
        'holiday_multiplier',
        'tax_percentage',
    ];

    protected function casts(): array
    {
        return [
            'standard_hours_per_week' => 'integer',
            'overtime_multiplier' => 'decimal:2',
            'holiday_multiplier' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
        ];
    }
}
