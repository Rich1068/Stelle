<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        {
            Paginator::useBootstrap();
       }
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
