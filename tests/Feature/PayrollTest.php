<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_and_hr_can_access_payroll_erp(): void
    {
        Role::findOrCreate('HR');
        Role::findOrCreate('Super Admin');

        $hrUser = User::factory()->create();
        $hrUser->assignRole('HR');

        $this->actingAs($hrUser);
        $response = $this->get(route('payroll.index'));
        $response->assertStatus(200);

        $adminUser = User::factory()->create();
        $adminUser->assignRole('Super Admin');

        $this->actingAs($adminUser);
        $response = $this->get(route('payroll.index'));
        $response->assertStatus(200);
    }

    public function test_regular_employee_cannot_access_payroll_erp(): void
    {
        Role::findOrCreate('Employee');

        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('Employee');

        $this->actingAs($employeeUser);
        $response = $this->get(route('payroll.index'));
        $response->assertStatus(403);
    }
}
