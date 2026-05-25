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

// Dashboard Routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

// Resource Routes
Route::resource('companies', CompanyController::class);
Route::resource('clients', ClientController::class);
Route::resource('projects', ProjectController::class);
Route::resource('tickets', TicketController::class);
Route::resource('tasks', TaskController::class);
Route::resource('staff', StaffController::class);

// Custom Ticket Comment Store Route
Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');
