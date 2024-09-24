<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Inicio de sesión en IMSS">
    <meta name="author" content="">
    <title>Login - IMSS</title>
    
    <link rel="stylesheet" href="css/estilos.css"> 
    <!-- SweetAlert2 CSS local -->
    <link rel="stylesheet" href="libs/SweetAlert2/sweetalert2.min.css">
    <!-- SweetAlert2 JS local -->
    <script src="libs/SweetAlert2/sweetalert2.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600&family=Poiret+One&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container-login"> 
    <div class="wrap-login"> 
        <form id="login-form" method="POST" action="procesar_login.php"> 
            <!-- LOGO --> 
            <span class="login-form-title">Inicio Sesión</span> 
            <img class="avatar" src="https://cdn-icons-png.flaticon.com/512/3135/3135789.png" alt="" align="center">
            
            <!-- USUARIO --> 
            <div class="wrap-input100"> 
                <input class="input100" type="email" id="email" aria-describedby="emailHelp" placeholder="Ingrese su correo" name="email" required>	 
                <span class="focus-efecto"></span> 
            </div> 

            <!-- CONTRASEÑA --> 
            <div class="wrap-input100"> 
                <input class="input100" type="password" id="password" placeholder="Contraseña" required> 
                <span class="focus-efecto"></span> 
            </div> 

            <!-- BOTÓN --> 
            <div class="container-login-form-btn"> 
                <div class="wrap-login-form-btn"> 
                    <div class="login-form-bgbtn"></div> 
                    <button type="submit" name="btn_inicio" class="login-form-btn">Iniciar Sesión</button> 
                </div> 
            </div> 
        </form> 
    </div> 
</div> 

<!-- Scripts necesarios -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="ajax/ajaxRegister.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>
