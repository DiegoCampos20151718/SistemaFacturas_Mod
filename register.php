<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registro - IMSS</title>
    <!-- SweetAlert2 CSS local -->
    <link rel="stylesheet" href="libs/SweetAlert2/sweetalert2.min.css">
    <!-- SweetAlert2 JS local -->
    <script src="libs/SweetAlert2/sweetalert2.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        body {
            background-color: #2d6a4f;
        }
        .card {
            border: none;
            border-radius: 10px;
        }
        .bg-register-image {
            background: url('img/Wavy_Tsp-01_Single-10-ai-2.png');
            background-position: center;
            background-size: cover;
        }
        .btn-primary {
            background-color: #1b4332;
            border-color: #1b4332;
        }
        .btn-primary:hover {
            background-color: #14532d;
            border-color: #14532d;
        }
        .form-control {
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card o-hidden shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">¡Regístrate!</h1>
                            </div>
                            <form class="user" id="register-form">
                                <div class="form-group row mb-3">
                                    <div class="col-sm-6">
                                        <label for="exampleFirstName">Nombre</label>
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="Nombre" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleLastName">Apellido</label>
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Apellido" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail">Correo</label>
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Correo" autocomplete="off">
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-sm-6">
                                        <label for="exampleInputPassword">Contraseña</label>
                                        <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Contraseña" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleRepeatPassword">Repetir Contraseña</label>
                                        <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repetir Contraseña" autocomplete="off">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Registrar Cuenta</button>
                                <hr>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">¿Ya tienes cuenta? ¡Inicia sesión!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="ajax/ajaxRegister.js"></script>
    <script src="ajax/jquery.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
