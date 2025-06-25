<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de correo</title>

    <!-- Bootstrap 4.5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            max-width: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-header {
            background-color: #203c6c;
            color: #fff;
            font-weight: bold;
            font-size: 1.25rem;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }

        .card-body {
            padding: 2rem;
            text-align: center;
        }

        .alert {
            margin-top: 1rem;
        }

        .btn-primary {
            background-color: #203c6c;
            border-color: #203c6c;
        }

        .btn-primary:hover {
            background-color: #1a3055;
            border-color: #1a3055;
        }

        p {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            Verificación de Correo
        </div>
        <div class="card-body">
            <h4 class="mb-3">Por favor, verifica tu correo electrónico</h4>
            <p>Antes de continuar, revisa tu correo para encontrar el enlace de verificación.</p>

            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary mt-3">Reenviar correo de verificación</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd"
        crossorigin="anonymous"></script>
</body>

</html>
