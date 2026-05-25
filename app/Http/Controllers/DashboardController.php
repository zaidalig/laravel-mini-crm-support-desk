<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\Task;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    /**
     * Display the system dashboard.
     */
    public function index()
    {
        $totalCompanies = Company::count();
        $totalClients = Client::count();
        $activeProjects = Project::where('status', 'in_progress')->count();
        $openTickets = Ticket::where('status', 'open')->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        
        // Count overdue tasks (due date in past, not completed)
        $overdueTasks = Task::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        // Recent activity logs (latest 10)
        $recentActivities = ActivityLog::latest()->take(10)->get();

        // Recent projects (latest 5)
        $recentProjects = Project::with(['company', 'client'])->latest()->take(5)->get();

        // Recent tickets (latest 5)
        $recentTickets = Ticket::with(['company', 'client'])->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalCompanies',
            'totalClients',
            'activeProjects',
            'openTickets',
            'pendingTasks',
            'overdueTasks',
            'recentActivities',
            'recentProjects',
            'recentTickets'
        ));
    }
}
