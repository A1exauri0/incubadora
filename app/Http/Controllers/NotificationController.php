<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Asegura que solo usuarios autenticados y verificados puedan acceder
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Obtiene las notificaciones no leídas para el usuario autenticado.
     * Solo accesible por el administrador.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadNotifications(Request $request)
    {
        $user = Auth::user();

        // Asegúrate de que solo los administradores puedan ver estas notificaciones
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        // Obtener las notificaciones no leídas
        // El método 'unreadNotifications' es proporcionado por el trait Notifiable
        $notifications = $user->unreadNotifications()->limit(10)->get(); // Limita a las 10 más recientes

        // Formatear las notificaciones para la vista
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'],
                'link' => $notification->data['link'] ?? '#', // Si no hay link, usa '#'
                'time' => $notification->created_at->diffForHumans(), // Muestra "hace X tiempo"
                'type' => $notification->data['type'] ?? 'general', // Incluye el tipo de notificación
            ];
        });

        return response()->json($formattedNotifications);
    }

    /**
     * Marca una notificación específica o todas las notificaciones no leídas como leídas.
     * Solo accesible por el administrador.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationsAsRead(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        // Si se proporciona un ID de notificación, marca solo esa como leída
        if ($notificationId = $request->input('id')) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            // Si no se proporciona un ID, marca todas las no leídas como leídas
            $user->unreadNotifications->markAsRead();
        }

        // Devolver el nuevo conteo de notificaciones no leídas
        $newUnreadCount = $user->unreadNotifications()->count();

        return response()->json(['success' => true, 'unread_count' => $newUnreadCount]);
    }
}
