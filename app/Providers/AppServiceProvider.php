<?php

namespace App\Providers;

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
        // Forzamos el uso de Bootstrap 5 para el paginado (evita flechas gigantes)
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
