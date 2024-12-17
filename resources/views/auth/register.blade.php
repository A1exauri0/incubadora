<!doctype html>
<html lang="en">

<head>
    <title>Registro</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>

<body>
    <section class="vh-100" style="background-color: #508bfc;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-5">Registro</h3>
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <input name="email" type="email" id="typeEmailX-2" class="form-control form-control-lg" required />
                                    <label class="form-label" for="typeEmailX-2">Correo electrónico</label>
                                </div>
                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="typePasswordX-2" class="form-control form-control-lg" required />
                                    <label class="form-label" for="typePasswordX-2">Contraseña</label>
                                </div>
                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                                    <label class="form-check-label" for="form1Example3"> Recordar contraseña </label>
                                </div>
                                <!-- Login button -->
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Acceder</button>
                                <hr class="my-4">
                                <!-- Social login buttons -->
                                <button class="btn btn-lg btn-block btn-danger" style="background-color: #dd4b39;" type="button">
                                    <i class="fab fa-google me-2"></i> Acceder con Google
                                </button>
                                <br><br>
                                <button class="btn btn-lg btn-block btn-primary mb-2" style="background-color: #3b5998;" type="button">
                                    <i class="fab fa-facebook-f me-2"></i> Acceder con Facebook
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>