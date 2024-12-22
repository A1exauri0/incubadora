<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incubadora</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color:rgb(43, 181, 206);
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100vh;
        }

        .title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .subtitle {
            font-size: 4rem;
            font-weight: lighter;
            color: #FFF;
            margin-bottom: 2rem;
        }

        .links {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .link {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            text-decoration: none;
        }

        .link:hover {
            color: #555;
        }

        .dark-mode .link {
            color: #ddd;
        }

        .dark-mode .link:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Incubadora</div>
        <div class="subtitle">de proyectos</div>

        <div class="links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="link">Home</a>
                @else
                    <a href="{{ route('login') }}" class="link">Iniciar sesión</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="link">Registro</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</body>
</html>
