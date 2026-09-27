<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        $frontendViews = base_path('../frontend/resources/views');
        View::addLocation($frontendViews);
        Blade::anonymousComponentPath($frontendViews.'/components');

        Paginator::useBootstrapFive();
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }
}
