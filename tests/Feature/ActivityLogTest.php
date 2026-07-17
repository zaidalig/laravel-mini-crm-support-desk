<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email,
            'password' => 'password',
            'role' => $role,
            'status' => 'active',
        ]);
    }

    public function test_owner_can_view_activity_logs(): void
    {
        $owner = $this->makeUser('owner', 'owner-activity@test.local');

        ActivityLog::create([
            'action' => 'Created',
            'module' => 'Companies',
            'description' => 'Created Company "Acme Corp"',
        ]);

        $this->actingAs($owner)
            ->get('/activity-logs')
            ->assertOk()
            ->assertSee('Created Company "Acme Corp"');
    }

    public function test_manager_can_view_activity_logs(): void
    {
        $manager = $this->makeUser('manager', 'manager-activity@test.local');

        $this->actingAs($manager)
            ->get('/activity-logs')
            ->assertOk();
    }

    /**
     * Gate choice: activity logs use can:manage-users (owner + manager only).
     * Support users have can:manage-crm but are intentionally blocked from audit logs.
     */
    public function test_support_cannot_view_activity_logs(): void
    {
        $support = $this->makeUser('support', 'support-activity@test.local');

        $this->actingAs($support)
            ->get('/activity-logs')
            ->assertForbidden();
    }
}
