<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'active.user'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // User and team management
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::patch('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');
    });
    Route::middleware('can:manage-teams')->group(function () {
        Route::resource('teams', TeamController::class);
    });

    // Resource Routes
    Route::middleware('can:manage-crm')->group(function () {
        Route::resource('companies', CompanyController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('tickets', TicketController::class);
        Route::resource('tasks', TaskController::class);
        Route::resource('staff', StaffController::class);

        // Custom Ticket Comment Store Route
        Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');
    });
});
