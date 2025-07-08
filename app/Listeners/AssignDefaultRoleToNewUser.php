<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User; // Importa el modelo User

class AssignDefaultRoleToNewUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        // Obtener el usuario que acaba de ser registrado
        $user = $event->user;

        // Asignar el rol 'alumno' al usuario
        // Asegúrate de que el rol 'alumno' exista en tu tabla 'roles'.
        // Si no existe, este método podría fallar o no hacer nada.
        // (Según tu tabla de roles, 'alumno' tiene id=2)
        $user->assignRole('alumno');

        // Opcional: Puedes loggear esto para depuración
        // \Log::info('Rol "alumno" asignado a nuevo usuario: ' . $user->email);
    }
}
