<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_view_dashboard(): void
    {
        $user = User::create([
            'name' => 'Test Owner',
            'email' => 'owner@test.local',
            'password' => 'password',
            'role' => 'owner',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function test_owner_can_access_user_and_team_management(): void
    {
        $user = User::create([
            'name' => 'Test Owner',
            'email' => 'owner-management@test.local',
            'password' => 'password',
            'role' => 'owner',
            'status' => 'active',
        ]);

        $this->actingAs($user)->get('/users')->assertStatus(200);
        $this->actingAs($user)->get('/teams')->assertStatus(200);
    }

    public function test_support_users_cannot_manage_users(): void
    {
        $user = User::create([
            'name' => 'Support User',
            'email' => 'support@test.local',
            'password' => 'password',
            'role' => 'support',
            'status' => 'active',
        ]);

        $this->actingAs($user)->get('/users')->assertForbidden();
    }
}
