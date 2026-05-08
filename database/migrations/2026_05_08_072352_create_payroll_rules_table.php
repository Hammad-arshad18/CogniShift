<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->integer('standard_hours_per_week')->default(40);
            $table->decimal('overtime_multiplier', 4, 2)->default(1.50);
            $table->decimal('holiday_multiplier', 4, 2)->default(2.00);
            $table->decimal('tax_percentage', 5, 2)->default(10.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_rules');
    }
};
