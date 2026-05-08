<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Tenant
        $tenant = \App\Models\Tenant::create([
            'name' => 'Acme Corporation',
            'domain' => 'acme.com',
            'plan_tier' => 'enterprise',
        ]);

        // 2. Set the tenant context so the HasTenant trait works automatically for subsequent models
        session()->put('tenant_id', $tenant->id);

        // 3. Create the Super Admin User
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'tenant_id' => $tenant->id,
            // password is 'password' by default in UserFactory
        ]);

        // 4. Create a Department
        $dept = \App\Models\Department::create([
            'name' => 'Engineering',
            'description' => 'Software development team',
            'tenant_id' => $tenant->id,
        ]);

        // 5. Create a Role
        $role = \App\Models\Role::create([
            'name' => 'Senior Developer',
            'description' => 'Writes complex code',
            'tenant_id' => $tenant->id,
        ]);

        // 6. Create an Employee record for the admin
        $employee = \App\Models\Employee::create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role_id' => $role->id,
            'date_of_joining' => now()->subYear(),
            'status' => 'active',
            'tenant_id' => $tenant->id,
        ]);

        // 7. Create some Payroll Rules
        \App\Models\PayrollRule::create([
            'standard_hours_per_week' => 40,
            'overtime_multiplier' => 1.5,
            'holiday_multiplier' => 2.0,
            'tax_percentage' => 12.5,
            'tenant_id' => $tenant->id,
        ]);
    }
}
