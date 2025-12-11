<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share admin's first name with all admin views
        View::composer('admin.*', function ($view) {
            if (auth()->check()) {
                $adminName = auth()->user()->name;
                $firstName = explode(' ', $adminName)[0] ?? $adminName;
                $view->with('firstName', $firstName);
            }
        });
        
        // Share user's first name with all user views
        View::composer('dashboard', function ($view) {
            if (auth()->check()) {
                $userName = auth()->user()->name;
                $firstName = explode(' ', $userName)[0] ?? $userName;
                $view->with('firstName', $firstName);
            }
        });
    }
}