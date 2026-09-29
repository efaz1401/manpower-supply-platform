<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use RefreshDatabase;

    private function ops(): User
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('ops');

        return $user;
    }

    public function test_ops_can_create_a_client(): void
    {
        $this->actingAs($this->ops());

        Volt::test('clients.index')
            ->set('name_en', 'Acme Contracting')
            ->set('name_ar', 'أكمي للمقاولات')
            ->set('payment_terms_days', 45)
            ->set('status', 'active')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('clients', [
            'name_en' => 'Acme Contracting',
            'payment_terms_days' => 45,
        ]);
    }

    public function test_validation_rejects_missing_name(): void
    {
        $this->actingAs($this->ops());

        Volt::test('clients.index')
            ->set('name_en', '')
            ->set('name_ar', '')
            ->call('save')
            ->assertHasErrors(['name_en', 'name_ar']);
    }

    public function test_ops_can_edit_a_client(): void
    {
        $client = Client::factory()->create(['name_en' => 'Old Name']);
        $this->actingAs($this->ops());

        Volt::test('clients.index')
            ->call('edit', $client->id)
            ->set('name_en', 'New Name')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('New Name', $client->fresh()->name_en);
    }

    public function test_ops_can_soft_delete_a_client(): void
    {
        $client = Client::factory()->create();
        $this->actingAs($this->ops());

        Volt::test('clients.index')->call('delete', $client->id);

        $this->assertSoftDeleted($client);
    }
}
