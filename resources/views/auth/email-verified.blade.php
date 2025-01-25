<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo Verificado</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Asegúrate de cargar tus estilos -->
</head>
<body class="bg-gray-100 text-center p-6">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6 mt-20">
        <h1 class="text-2xl font-bold text-green-600 mb-4">¡Correo verificado con éxito!</h1>
        <p class="text-gray-700 mb-6">Gracias por verificar tu correo. Ahora puedes acceder a todas las funcionalidades de la plataforma.</p>
        <a href="{{ route('home') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Ir a Inicio
        </a>
    </div>
</body>
</html>
