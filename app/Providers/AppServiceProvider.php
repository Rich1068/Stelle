<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::composer('layouts.app', function ($view) {
            $user = auth()->user(); // Fetch the currently authenticated user
            $sidebar = '';

            switch ($user->role_id) {
                case '1':
                    $sidebar = 'super_admin.partials.sidebar';
                    break;
                case '2':
                    $sidebar = 'admin.partials.sidebar';
                    break;
                // Add more cases as needed
                case '3':
                    $sidebar = 'user.partials.sidebar';
                    break;
            }

            $view->with('sidebar', $sidebar); // Pass the sidebar name to the view
        });
    }
}
