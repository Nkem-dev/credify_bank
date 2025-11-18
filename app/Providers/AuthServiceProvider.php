<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('access-admin-dashboard', function (User $user){
            return $user->role === 'admin';
        });

        Gate::define('access-user-dashboard', function (User $user){
            return $user->role === 'user';
        });

         Gate::define('access-customer_care-dashboard', function (User $user){
            return $user->role === 'customer_care';
        });
    }
}
