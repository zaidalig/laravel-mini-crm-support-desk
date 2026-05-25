<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\Company;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Task;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = collect([
            ['name' => 'System Owner', 'email' => 'owner@example.com', 'password' => 'password', 'role' => 'owner', 'status' => 'active'],
            ['name' => 'Project Manager', 'email' => 'manager@example.com', 'password' => 'password', 'role' => 'manager', 'status' => 'active'],
            ['name' => 'Support Agent', 'email' => 'support@example.com', 'password' => 'password', 'role' => 'support', 'status' => 'active'],
            ['name' => 'Read Only User', 'email' => 'viewer@example.com', 'password' => 'password', 'role' => 'viewer', 'status' => 'inactive'],
        ])->map(fn (array $user) => User::create($user));

        $companies = collect([
            [
                'name' => 'Northstar Digital',
                'email' => 'hello@northstar.test',
                'phone' => '+1 555 0101',
                'industry' => 'SaaS',
                'city' => 'Austin',
                'address' => '120 Market Street, Austin, TX',
                'status' => 'active',
            ],
            [
                'name' => 'Greenline Logistics',
                'email' => 'ops@greenline.test',
                'phone' => '+1 555 0102',
                'industry' => 'Logistics',
                'city' => 'Chicago',
                'address' => '44 Harbor Avenue, Chicago, IL',
                'status' => 'active',
            ],
            [
                'name' => 'BrightCare Clinics',
                'email' => 'support@brightcare.test',
                'phone' => '+1 555 0103',
                'industry' => 'Healthcare',
                'city' => 'Seattle',
                'address' => '81 Wellness Road, Seattle, WA',
                'status' => 'inactive',
            ],
        ])->map(fn (array $company) => Company::create($company));

        $staff = collect([
            ['name' => 'Ayesha Khan', 'email' => 'ayesha@desk.test', 'role' => 'Support Lead', 'status' => 'active'],
            ['name' => 'Daniel Brooks', 'email' => 'daniel@desk.test', 'role' => 'Project Manager', 'status' => 'active'],
            ['name' => 'Mina Patel', 'email' => 'mina@desk.test', 'role' => 'Frontend Developer', 'status' => 'active'],
            ['name' => 'Omar Reyes', 'email' => 'omar@desk.test', 'role' => 'QA Analyst', 'status' => 'inactive'],
        ])->map(fn (array $member) => Staff::create($member));

        $clients = collect([
            ['company_id' => $companies[0]->id, 'name' => 'Emma Stone', 'email' => 'emma@northstar.test', 'phone' => '+1 555 0201', 'designation' => 'Product Director', 'status' => 'active'],
            ['company_id' => $companies[0]->id, 'name' => 'Liam Carter', 'email' => 'liam@northstar.test', 'phone' => '+1 555 0202', 'designation' => 'Operations Manager', 'status' => 'active'],
            ['company_id' => $companies[1]->id, 'name' => 'Sophia Reed', 'email' => 'sophia@greenline.test', 'phone' => '+1 555 0203', 'designation' => 'Logistics Coordinator', 'status' => 'active'],
            ['company_id' => $companies[2]->id, 'name' => 'Noah Kim', 'email' => 'noah@brightcare.test', 'phone' => '+1 555 0204', 'designation' => 'Clinic Administrator', 'status' => 'inactive'],
        ])->map(fn (array $client) => Client::create($client));

        $projects = collect([
            [
                'company_id' => $companies[0]->id,
                'client_id' => $clients[0]->id,
                'title' => 'Customer Portal Refresh',
                'description' => 'Improve the customer self-service portal and reporting pages.',
                'budget' => 18500,
                'start_date' => now()->subDays(30)->toDateString(),
                'deadline' => now()->addDays(20)->toDateString(),
                'progress' => 65,
                'status' => 'in_progress',
            ],
            [
                'company_id' => $companies[1]->id,
                'client_id' => $clients[2]->id,
                'title' => 'Fleet Support Dashboard',
                'description' => 'Build dashboard screens for tracking fleet maintenance issues.',
                'budget' => 12400,
                'start_date' => now()->subDays(50)->toDateString(),
                'deadline' => now()->subDays(4)->toDateString(),
                'progress' => 40,
                'status' => 'in_progress',
            ],
            [
                'company_id' => $companies[2]->id,
                'client_id' => $clients[3]->id,
                'title' => 'Clinic Intake Migration',
                'description' => 'Move legacy intake forms into the new CRM workflow.',
                'budget' => 9000,
                'start_date' => now()->subDays(90)->toDateString(),
                'deadline' => now()->subDays(10)->toDateString(),
                'progress' => 100,
                'status' => 'completed',
            ],
        ])->map(fn (array $project) => Project::create($project));

        $tickets = collect([
            [
                'company_id' => $companies[0]->id,
                'client_id' => $clients[1]->id,
                'title' => 'Login redirects to old portal',
                'description' => 'Some users are redirected to the old portal after signing in.',
                'priority' => 'urgent',
                'status' => 'open',
                'assigned_to' => $staff[0]->id,
                'due_date' => now()->addDay()->toDateString(),
            ],
            [
                'company_id' => $companies[1]->id,
                'client_id' => $clients[2]->id,
                'title' => 'Exported CSV misses vehicle status',
                'description' => 'The fleet export does not include the current vehicle status column.',
                'priority' => 'high',
                'status' => 'in_progress',
                'assigned_to' => $staff[2]->id,
                'due_date' => now()->subDays(2)->toDateString(),
            ],
            [
                'company_id' => $companies[2]->id,
                'client_id' => $clients[3]->id,
                'title' => 'Archive resolved intake ticket',
                'description' => 'Confirm the resolved intake migration issue can be closed.',
                'priority' => 'low',
                'status' => 'resolved',
                'assigned_to' => $staff[1]->id,
                'due_date' => now()->addDays(5)->toDateString(),
            ],
        ])->map(fn (array $ticket) => Ticket::create($ticket));

        TicketComment::create([
            'ticket_id' => $tickets[0]->id,
            'comment' => 'Client confirmed the redirect happens for users created before the portal refresh.',
            'commented_by' => 'Ayesha Khan',
            'is_internal_note' => false,
        ]);

        TicketComment::create([
            'ticket_id' => $tickets[0]->id,
            'comment' => 'Check legacy SSO callback URLs before changing middleware.',
            'commented_by' => 'Daniel Brooks',
            'is_internal_note' => true,
        ]);

        TicketComment::create([
            'ticket_id' => $tickets[1]->id,
            'comment' => 'Patch is ready for QA review on the staging export screen.',
            'commented_by' => 'Mina Patel',
            'is_internal_note' => false,
        ]);

        collect([
            [
                'project_id' => $projects[0]->id,
                'ticket_id' => null,
                'title' => 'Polish account overview cards',
                'description' => 'Finalize Bootstrap card spacing and empty states.',
                'assigned_to' => $staff[2]->id,
                'due_date' => now()->addDays(3)->toDateString(),
                'status' => 'in_progress',
                'priority' => 'medium',
            ],
            [
                'project_id' => $projects[1]->id,
                'ticket_id' => null,
                'title' => 'Review overdue fleet alerts',
                'description' => 'Confirm alert rules for vehicles older than service window.',
                'assigned_to' => $staff[1]->id,
                'due_date' => now()->subDays(1)->toDateString(),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'project_id' => null,
                'ticket_id' => $tickets[0]->id,
                'title' => 'Fix SSO redirect callback',
                'description' => 'Update callback validation for legacy accounts.',
                'assigned_to' => $staff[0]->id,
                'due_date' => now()->addDay()->toDateString(),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'project_id' => null,
                'ticket_id' => $tickets[1]->id,
                'title' => 'Add status to CSV export',
                'description' => 'Include vehicle status in exported reports.',
                'assigned_to' => $staff[2]->id,
                'due_date' => now()->addDays(2)->toDateString(),
                'status' => 'completed',
                'priority' => 'medium',
            ],
        ])->each(fn (array $task) => Task::create($task));

        $implementationTeam = Team::create([
            'name' => 'Implementation Team',
            'description' => 'Handles active CRM implementation and project delivery work.',
            'team_lead_id' => $users[1]->id,
            'status' => 'active',
        ]);

        $implementationTeam->users()->sync([
            $users[1]->id => ['member_role' => 'Team Lead'],
            $users[2]->id => ['member_role' => 'Member'],
        ]);

        $implementationTeam->projects()->sync([$projects[0]->id, $projects[1]->id]);

        $supportTeam = Team::create([
            'name' => 'Support Desk Team',
            'description' => 'Manages incoming tickets, urgent issues, and client support follow-up.',
            'team_lead_id' => $users[0]->id,
            'status' => 'active',
        ]);

        $supportTeam->users()->sync([
            $users[0]->id => ['member_role' => 'Team Lead'],
            $users[2]->id => ['member_role' => 'Member'],
        ]);

        $supportTeam->projects()->sync([$projects[0]->id]);

        ActivityLog::create([
            'action' => 'Seeded',
            'module' => 'Dashboard',
            'description' => 'Loaded sample companies, clients, projects, tickets, tasks, staff, and comments.',
            'created_at' => now()->subMinutes(5),
            'updated_at' => now()->subMinutes(5),
        ]);
    }
}
