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
    // Constantes para las etapas, ¡es importante que estén aquí o sean accesibles!
    const ID_ETAPA_PENDIENTE_ASESOR = 1; 
    const ID_ETAPA_VISTO_BUENO_ASESOR = 2;
    const ID_ETAPA_APROBADA_ADMIN = 3;   
    const ID_ETAPA_RECHAZADA = 4;

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
        // View Composer para los layouts principales
        View::composer(['layouts.layout', 'layouts.layoutCrud', 'layouts.layoutParticipantes'], function ($view) {
            $isLeader = false;
            $unreadNotificationsCount = 0;
            $revisionesAdminCount = 0;
            $revisionesAsesorCount = 0;

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

                // Lógica para contar notificaciones no leídas y revisiones pendientes
                if ($user->hasAnyRole(['admin', 'asesor'])) {
                     $unreadNotificationsCount = $user->unreadNotifications()->count();
                }

                // Obtener el conteo de revisiones pendientes para el ADMIN
                if ($user->hasRole('admin')) {
                    $revisionesAdminCount = DB::table('proyecto')
                        ->where('etapa', self::ID_ETAPA_VISTO_BUENO_ASESOR)
                        ->count();
                }

                // Obtener el conteo de revisiones pendientes para el ASESOR
                if ($user->hasRole('asesor')) {
                    $asesor = DB::table('asesor')->where('correo_electronico', $user->email)->first();
                    if ($asesor) {
                        $revisionesAsesorCount = DB::table('asesor_proyecto')
                            ->where('idAsesor', $asesor->idAsesor)
                            ->join('proyecto', 'asesor_proyecto.clave_proyecto', '=', 'proyecto.clave_proyecto')
                            ->where('proyecto.etapa', self::ID_ETAPA_PENDIENTE_ASESOR)
                            ->count();
                    }
                }
            }
            
            // Compartir las variables con la vista
            $view->with('isLeader', $isLeader);
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
            $view->with('revisionesAdminCount', $revisionesAdminCount);
            $view->with('revisionesAsesorCount', $revisionesAsesorCount);
        });

        // Personalización del correo de verificación de email
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu dirección de correo electrónico - IncubaTec ITTG')
                ->view('emails.verify_email', [
                    'actionUrl' => $url,
                    'actionText' => 'Verificar correo',
                    'displayableActionUrl' => $url,
                ]);
        });
    }
}