<!doctype html>
<html lang="en">

<head>
    <title>Registro</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="icon" href="{{ asset('images/logo_tec.png') }}" type="image/x-icon" />

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #ffffff, #f0f0f0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 0;
            overflow: hidden;
            width: 90%;
            max-width: 900px;
        }

        .login-image-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-image-section img {
            max-width: 100%;
            height: auto;
        }

        .login-form-section {
            padding: 3rem;
        }

        .form-control {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            background-color: #f7f7f7;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #2596be;
        }

        .btn-primary {
            background-color: #2596be;
            border-color: #2596be;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #1e7a9b;
            border-color: #1e7a9b;
        }

        .alert-danger ul {
            padding-left: 20px;
            margin-bottom: 0;
            text-align: left;
        }

        @media (max-width: 768px) {
            .login-container {
                width: 95%;
            }

            .login-form-section {
                padding: 2rem;
            }
        }

        .btn-block {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div class="login-container row no-gutters">

            <div class="col-md-6 login-image-section d-none d-md-flex">
                <img src="images/logo_tec.png" class="img-fluid" alt="Imagen de Registro">
            </div>

            <div class="col-md-6 login-form-section">
                <h3 class="mb-4 text-center">Registro</h3>
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex flex-row align-items-center mb-4">
                        <i class="fas fa-user fa-lg me-3 fa-fw text-secondary"></i>
                        <input name="name" type="text" class="form-control" placeholder="Nombre"
                            value="{{ old('name') }}" required />
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <i class="fas fa-envelope fa-lg me-3 fa-fw text-secondary"></i>
                        <input name="email" type="email" class="form-control" placeholder="Correo electrónico"
                            value="{{ old('email', request('email')) }}"
                            {{ request('email') && request('token') ? 'readonly' : '' }} required />
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <i class="fas fa-lock fa-lg me-3 fa-fw text-secondary"></i>
                        <input name="password" type="password" class="form-control" placeholder="Contraseña" required />
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <i class="fas fa-key fa-lg me-3 fa-fw text-secondary"></i>
                        <input name="password_confirmation" type="password" class="form-control"
                            placeholder="Confirmar contraseña" required />
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4 {{ request('token') ? '' : 'd-none' }}"
                        id="token-input-group">
                        <i class="fas fa-barcode fa-lg me-3 fa-fw text-secondary"></i>
                        <input name="token" type="text" class="form-control" placeholder="Token de Registro"
                            value="{{ old('token', request('token')) }}" autocomplete="off"
                            {{ request('token') ? 'readonly required' : '' }} />
                    </div>

                    @if (!request('token'))
                        <div class="d-grid gap-2 mb-4">
                            <button type="button" class="btn btn-outline-info btn-sm" id="toggleTokenField">
                                ¿Tienes un token de registro?
                            </button>
                        </div>
                    @endif

                    <button class="btn btn-primary btn-lg" type="submit">Registrarse</button>


                    <hr class="my-4">

                    <p class="text-center">
                        ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
                    </p>

                </form>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"        
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"        
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tokenInputFieldGroup = document.getElementById('token-input-group');
            const toggleButton = document.getElementById('toggleTokenField');
            const tokenInput = document.querySelector('input[name="token"]');

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const isHidden = tokenInputFieldGroup.classList.toggle('d-none');
                    if (isHidden) {
                        tokenInput.removeAttribute('required');
                        tokenInput.value = '';
                        this.textContent = '¿Tienes un token de registro?';
                    } else {
                        tokenInput.setAttribute('required', 'required');
                        this.textContent = 'Ocultar campo de token';
                    }
                });
            }
        });
    </script>
</body>

</html>
