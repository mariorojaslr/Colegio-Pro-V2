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
        // Forzar HTTPS en producción (evita bloqueo de Mixed Content para assets)
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

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

        // Traducir correo de restablecimiento de contraseña
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Recuperación de Contraseña')
                ->greeting('¡Hola!')
                ->line('Estás recibiendo este correo porque solicitaste un restablecimiento de contraseña para tu cuenta.')
                ->action('Restablecer Contraseña', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('Este enlace de restablecimiento de contraseña expirará en 60 minutos.')
                ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.');
        });
    }
}
