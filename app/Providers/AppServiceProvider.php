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

        // Inyectar contador de chatbot pendiente para el menú de administrador
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (auth()->check() && in_array(auth()->user()->role, ['ADMIN_COLEGIO', 'OWNER'])) {
                $pendingChatbotCount = \App\Models\ChatbotKnowledge::where('school_id', auth()->user()->school_id)
                                        ->where('status', 'pending')
                                        ->count();
                $view->with('pendingChatbotCount', $pendingChatbotCount);
            }
        });
    }
}
