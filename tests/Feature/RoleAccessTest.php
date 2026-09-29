<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_ops_can_reach_back_office_modules(): void
    {
        $ops = $this->userWithRole('ops');

        foreach (['workers', 'clients', 'deployments', 'timesheets', 'payroll', 'invoices', 'recruitment', 'compliance'] as $slug) {
            $this->actingAs($ops)->get("/{$slug}")->assertOk();
        }
    }

    public function test_client_cannot_reach_back_office(): void
    {
        $client = $this->userWithRole('client');

        $this->actingAs($client)->get('/workers')->assertForbidden();
        $this->actingAs($client)->get('/payroll')->assertForbidden();
    }

    public function test_worker_cannot_reach_admin(): void
    {
        $worker = $this->userWithRole('worker');

        $this->actingAs($worker)->get('/admin/users')->assertForbidden();
    }

    public function test_only_admin_reaches_admin_users(): void
    {
        $this->actingAs($this->userWithRole('admin'))->get('/admin/users')->assertOk();
        $this->actingAs($this->userWithRole('manager'))->get('/admin/users')->assertForbidden();
    }

    public function test_executive_dashboard_restricted_to_admin_and_manager(): void
    {
        $this->actingAs($this->userWithRole('admin'))->get('/executive')->assertOk();
        $this->actingAs($this->userWithRole('manager'))->get('/executive')->assertOk();
        $this->actingAs($this->userWithRole('ops'))->get('/executive')->assertForbidden();
    }
}
