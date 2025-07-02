<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Asegúrate de que esta línea esté aquí

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
                    // Asegúrate de que tu modelo User usa el trait Notifiable
                    // (use Illuminate\Notifications\Notifiable; en App\Models\User)
                    $unreadNotificationsCount = $user->unreadNotifications()->count();
                }
            }
            // Compartir las variables con la vista
            $view->with('isLeader', $isLeader);
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Confirma tu dirección de correo electrónico')
                ->line('Por favor, haz clic en el siguiente botón para verificar tu correo.')
                ->action('Verificar correo', $url);
        });
    }
}
