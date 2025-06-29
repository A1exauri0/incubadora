<!doctype html>
<html lang="en">

<head>
    <title>Registro</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #2596be;
        }
        .card {
            border-radius: 1rem;
        }
        .btn-block {
            width: 100%;
        }
        .form-control-lg {
            height: calc(2.5rem + 2px); /* Ajuste para Bootstrap 5 */
        }
        .form-control::placeholder {
            color: #6c757d; /* Color más suave para el placeholder */
            opacity: 1;
        }
        .alert {
            margin-top: 1.5rem;
            text-align: left; /* Para que las listas de errores se vean bien */
        }
        /* Estilo para campos de solo lectura */
        .form-control[readonly] {
            background-color: #e9ecef; /* Un fondo gris claro para campos de solo lectura */
            opacity: 1; /* Asegurar que no se vea deshabilitado de forma extraña */
            cursor: not-allowed; /* Indicar que no es editable */
        }
    </style>
</head>

<body>
    <section class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-5">Registro</h3>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                <!-- Name input -->
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                    <input name="name" type="text" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           placeholder="Nombre" value="{{ old('name') }}" required autocomplete="name" />
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Email input -->
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                    <input name="email" type="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           placeholder="Correo electrónico" value="{{ old('email', request('email')) }}" required autocomplete="email"
                                           {{ request('email') && request('token') ? 'readonly' : '' }} /> {{-- CAMBIO: Añadido readonly condicional --}}
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Password input -->
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                    <input name="password" type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           placeholder="Contraseña" required autocomplete="new-password" />
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Password confirmation input -->
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                    <input name="password_confirmation" type="password" id="password_confirmation" class="form-control form-control-lg"
                                           placeholder="Confirmar contraseña" required autocomplete="new-password" />
                                </div>

                                <!-- Bloque de Token movido aquí, debajo de confirmar contraseña -->
                                <div class="d-flex flex-row align-items-center mb-4 {{ request('token') ? '' : 'd-none' }}" id="token-input-group">
                                    <i class="fas fa-barcode fa-lg me-3 fa-fw"></i>
                                    <input name="token" type="text" id="token" class="form-control form-control-lg @error('token') is-invalid @enderror"
                                           placeholder="Token de Registro" value="{{ old('token', request('token')) }}" autocomplete="off"
                                           {{ request('token') ? 'required readonly' : '' }} /> {{-- CAMBIO CLAVE: Añadido 'readonly' --}}
                                    @error('token')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Botón para revelar/ocultar el campo del token --}}
                                @if (!request('token')) {{-- Solo mostrar si el token no viene ya en la URL --}}
                                <div class="d-grid gap-2 mb-4">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="toggleTokenField">
                                        ¿Tienes un token de registro?
                                    </button>
                                </div>
                                @endif

                                <!-- Botón de registro -->
                                <button class="btn btn-primary btn-lg btn-block mb-3" type="submit">Registrarse</button>

                                <!-- Botón de regresar -->
                                <a href="{{ route('login') }}" class="btn btn-secondary btn-lg btn-block">Regresar al Inicio de Sesión</a>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        xintegrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        xintegrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tokenInputFieldGroup = document.getElementById('token-input-group');
            const toggleButton = document.getElementById('toggleTokenField');
            const tokenInput = document.getElementById('token');
            const emailInput = document.getElementById('email');

            // --- Lógica para el botón de alternar el campo del token ---
            if (toggleButton) { // Solo si el botón existe (es decir, si el token NO vino en la URL)
                toggleButton.addEventListener('click', function() {
                    tokenInputFieldGroup.classList.toggle('d-none'); // Alternar visibilidad

                    if (!tokenInputFieldGroup.classList.contains('d-none')) {
                        tokenInput.setAttribute('required', 'required'); // Hacer el campo requerido
                        tokenInput.removeAttribute('readonly'); // Asegurarse de que sea editable si se muestra manualmente
                        toggleButton.textContent = 'Ocultar campo de token';
                    } else {
                        tokenInput.removeAttribute('required'); // Ya no es requerido
                        tokenInput.value = ''; // Limpiar el valor al ocultar
                        tokenInput.removeAttribute('readonly'); // Remover readonly si se oculta
                        toggleButton.textContent = '¿Tienes un token de registro?';
                    }
                });
            }

            // --- Lógica para readonly en email y token (si vienen de la URL) ---
            const urlParams = new URLSearchParams(window.location.search);
            const emailFromUrl = urlParams.get('email');
            const tokenFromUrl = urlParams.get('token');

            if (emailFromUrl && tokenFromUrl) {
                // Ambos campos (email y token) se hacen readonly si vienen de la URL con ambos parámetros
                emailInput.setAttribute('readonly', 'readonly');
                // emailInput.classList.add('bg-light'); // Opcional: estilo visual
                tokenInput.setAttribute('readonly', 'readonly');
                // tokenInput.classList.add('bg-light'); // Opcional: estilo visual
            } else {
                // Asegurarse de que no sean readonly si no cumplen la condición
                emailInput.removeAttribute('readonly');
                // emailInput.classList.remove('bg-light');
                // Solo si el token no viene de la URL, asegurarse de que no sea readonly
                if (!tokenFromUrl) {
                     tokenInput.removeAttribute('readonly');
                     // tokenInput.classList.remove('bg-light');
                }
            }

            // Asegurar que el campo de token sea requerido si ya tiene un valor (ej. desde la URL)
            // Esto se mantiene en el JS por robustez, aunque ya lo hace Blade con {{ request('token') ? 'required' : '' }}
            if (tokenInput.value.length > 0) {
                tokenInput.setAttribute('required', 'required');
            }
        });
    </script>
</body>

</html>
