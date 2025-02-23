@extends('layout')

<body>
    <center>
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6 mt-20">
            <br><br><br><br><br><br><br><br> 
            <h1 class="text-2xl font-bold text-green-600 mb-4">¡Correo verificado con éxito!</h1>
            <br><br>
            <p class="text-gray-700 mb-6">Gracias por verificar tu correo. Ahora puedes acceder a todas las funcionalidades de la plataforma.</p>
            <br><br><br>
            <a href="{{ route('registro-datos') }}" class="btn btn-success">Ir a Inicio</a>
        </div>
    </center>
</body>

    
