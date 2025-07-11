<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
        // View Composer para el layout principal (donde está la barra de navegación)
        View::composer('layouts.layout', function ($view) {
            $isLeader = false; // Lógica existente para el líder
            $unreadNotificationsCount = 0; // Inicializar contador de notificaciones

            if (Auth::check()) {
                /** @var \App\Models\User */

                $user = Auth::user();

                // Lógica existente para determinar si es líder
                if ($user->hasRole('alumno')) {
                    $alumno = DB::table('alumno')->where('correo_institucional', $user->email)->first();
                    if ($alumno) {
                        $leaderEntry = DB::table('alumno_proyecto')
                            ->where('no_control', $alumno->no_control)
                            ->where('lider', 1)
                            ->first();
                        if ($leaderEntry) {
                            $isLeader = true;
                        }
                    }
                }

                // Lógica para contar notificaciones no leídas para el administrador
                if ($user->hasRole('admin')) {
                    $unreadNotificationsCount = $user->unreadNotifications()->count();
                }
            }
            // Compartir las variables con la vista
            $view->with('isLeader', $isLeader);
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        });

        // Personalización del correo de verificación de email
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            // Renderiza la vista Blade personalizada como HTML
            return (new MailMessage)
                ->subject('Verifica tu dirección de correo electrónico - IncubaTec ITTG')
                ->view('emails.verify_email', [
                    'actionUrl' => $url,
                    'actionText' => 'Verificar correo', // Texto del botón
                    'displayableActionUrl' => $url, // Para el subcopy
                ]);
        });
    }
}
