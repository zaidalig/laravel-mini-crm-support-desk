<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-users', fn ($user) => $user->canManageUsers());
        Gate::define('manage-teams', fn ($user) => $user->canManageTeams());
        Gate::define('manage-crm', fn ($user) => $user->canManageCrm());
    }
}
