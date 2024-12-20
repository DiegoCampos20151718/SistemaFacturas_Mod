<?php
// Include session check and database connection
include("php/check_session.php");
include("php/database.php");

// Obtener unidad perteneciente

// Validar si la sesión contiene la oficina
if (isset($_SESSION['unidad']) && is_numeric($_SESSION['unidad'])) {

    // Prepara la consulta para evitar inyección SQL
    $stmt = $connecction->prepare("SELECT nombre,unidad FROM unidades WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['unidad']); // "i" indica que es un entero

    $stmt->execute();
    $result = $stmt->get_result();

    $uniCor = "";

    // Verifica si hay resultados y guarda el nombre en una variable
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $uniCor = $row['unidad'];
        $uniCor2 = $row['nombre'];
    } else {
        $uniCor = "No se encontró la unidad";
        $uniCor2 = "No se encontro la unidad";
    }

    $stmt->close(); // Cerrar la declaración preparada

} else {
    echo "No se ha establecido una oficina en la sesión.";
}

if (isset($_SESSION['oficina']) && is_numeric($_SESSION['oficina'])) {

    // Prepara la consulta para evitar inyección SQL
    $stmt = $connecction->prepare("SELECT oficina FROM oficinas WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['oficina']); // "i" indica que es un entero

    $stmt->execute();
    $result = $stmt->get_result();

    $ofCor = "";

    // Verifica si hay resultados y guarda el nombre en una variable
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ofCor = $row['oficina'];
    } else {
        $ofCor = "No se encontró la unidad";
    }

    $stmt->close(); // Cerrar la declaración preparada

} else {
    echo "No se ha establecido una oficina en la sesión.";
}

// Obtener oficinas
$sql = "SELECT id, oficina FROM oficinas";
$result = $connecction->query($sql);
$oficinas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $oficinas[] = $row;
    }
}

// Obtener unidades
$sql1 = "SELECT id, nombre, unidad FROM unidades";
$result1 = $connecction->query($sql1);
$unidades = [];
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $unidades[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $matricula = $_POST['matricula'];
    $rol = $_POST['rol'];
    $oficina = $_POST['oficina'];
    $unidad = $_POST['unidad'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    // Validación de campos vacíos
    if (empty($nombre) || empty($apellido) || empty($matricula) || empty($rol) || empty($oficina) || empty($unidad) || empty($password) || empty($repeatPassword)) {
        echo "<script>Swal.fire('Error', 'Por favor, complete todos los campos', 'error');</script>";
    } elseif ($password != $repeatPassword) {
        echo "<script>Swal.fire('Error', 'Las contraseñas no coinciden', 'error');</script>";
    } elseif (!in_array($oficina, array_column($oficinas, 'id')) || !in_array($unidad, array_column($unidades, 'id'))) {
        echo "<script>Swal.fire('Error', 'Oficina o unidad seleccionada no es válida', 'error');</script>";
    } else {
        // Insertar el usuario en la base de datos
        $stmt = $connecction->prepare("INSERT INTO usuarios (nombre, apellido, matricula, rol, oficina, unidad, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sssssss", $nombre, $apellido, $matricula, $rol, $oficina, $unidad, $hashedPassword);

            if ($stmt->execute()) {
                echo "<script>Swal.fire('Éxito', 'Usuario registrado con éxito', 'success');</script>";
            } else {
                echo "<script>Swal.fire('Error', 'Hubo un problema al registrar el usuario', 'error');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>Swal.fire('Error', 'No se pudo preparar la consulta', 'error');</script>";
        }
    }
    $connecction->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registro Disponibilidad</title>
    <!-- SweetAlert2 CSS local -->
    <link rel="stylesheet" href="libs/SweetAlert2/sweetalert2.min.css">
    <!-- SweetAlert2 JS local -->
    <script src="libs/SweetAlert2/sweetalert2.min.js"></script>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Icons boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="#">
    <!-- Custom styles for this template-->
    <link rel="shortcut icon" href="img/imss-logo-gret.png" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon center">
                    <img src="img/imsslogo.png" alt="IMSS Logo" style="width: 50px; height: 50px;">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                menu
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <?php if ($_SESSION["rol"] == 1 || $_SESSION["rol"] ==  2): ?>
                <li class="nav-item">
                <a class="nav-link" href="RegistroFactura.php">
                    <i class="fas fa-fw fa fa-archive"></i>
                    <span>Registrar Factura</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="RegistroDisponibilidad.php">
                    <i class="fas fa-fw fa fa-archive"></i>
                    <span>Registrar disponibilidad</span></a>
            </li>
           <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="Facturas.php">
                    <i class="fas fa-fw fa-solid fa-file"></i>
                    <span>Facturas</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Contratos.php">
                    <i class="fas fa-fw fa-regular fa-file"></i>
                    <span>Contratos</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Mas acciones
            </div>
            <!-- Nav Item - Proveedores -->
            <li class="nav-item">
                <a class="nav-link" href="Proveedores.php">
                    <i class="fas fa-fw fa-solid fa-truck-moving"></i>
                    <span>Proveedores</span></a>
            </li>

            <!-- Nav Item - Contratos -->
            <li class="nav-item">
                <a class="nav-link" href="Graficas.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Graficas</span></a>
            </li>

            <!-- Nav Item - Graficas -->
            <li class="nav-item">
                <a class="nav-link" href="DisponibleContratos.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Disponible en contratos</span></a>
            </li>

            <?php if ($_SESSION["rol"] == 1): ?>
                <li class="nav-item">
                    <a class="nav-link" href="registerUser.php">
                        <i class="fas fa-fw fa-user-plus"></i>
                        <span>Registro de nuevo usuario</span></a>
                </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto d-flex justify-content-start">
                        <li class="nav-item" style="margin-right: 5rem; margin-top: 0.5rem; position: absolute; left: 0;">
                            <span class="mr-2 d-none d-lg-inline text-gray-600"
                                style="font-size: 1.5rem; font-weight: bold;">
                                Unidad: <?php echo $uniCor; ?> (<?php echo $uniCor2; ?>)
                            </span>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php
                                function rol($rol)
                                {
                                    switch ($rol) {
                                        case 1:
                                            return "administrador";
                                        case 2:
                                            return "Usuario de oficina";
                                        case 3:
                                            return "Usuario para consulta";
                                        default:
                                            return "desconocido";
                                    }
                                }
                                echo $ofCor . "<br>" . $_SESSION["nombre"] . " " . $_SESSION["apellido"] . "<br> " . rol($_SESSION["rol"]);
                                ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Sesion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->


                <div class="card o-hidden shadow-lg my-5 mx-auto" style="max-width: 500px;">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row justify-content-center align-items-center">
                            <div class="col-lg-12">
                                <div class="p-3">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">¡Regístrate!</h1>
                                    </div>
                                    <form class="user" id="register-form" method="POST" action="">
                                        <div class="form-group row mb-3">
                                            <div class="col-sm-6">
                                                <label for="exampleFirstName">Nombre</label>
                                                <input type="text" class="form-control" id="exampleFirstName"
                                                    name="nombre" placeholder="Nombre" autocomplete="off">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="exampleLastName">Apellido</label>
                                                <input type="text" class="form-control" id="exampleLastName"
                                                    name="apellido" placeholder="Apellido" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="exampleInputEmail">Matricula</label>
                                            <input type="text" class="form-control" id="exampleInputEmail"
                                                name="matricula" placeholder="Matricula" autocomplete="off">
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-sm-6">
                                                <label for="rol" class="form-label">Rol de usuario:</label>
                                                <select class="form-select" id="rol" name="rol" required>
                                                    <option selected>Rol de usuario</option>
                                                    <option value="1">Administrador</option>
                                                    <option value="2">Usuario de oficina</option>
                                                    <option value="3">Usuario de consulta</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="oficinas" class="form-label">Oficinas:</label>
                                                <select class="form-select" id="oficinas" name="oficina" required>
                                                    <option selected>Selecciona la oficina correspondiente</option>
                                                    <?php foreach ($oficinas as $oficina): ?>
                                                        <option value="<?php echo $oficina['id']; ?>">
                                                            <?php echo $oficina['id'] . ' - ' . $oficina['oficina']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="unidades" class="form-label">Unidades:</label>
                                                <select class="form-select" id="unidades" name="unidad" required>
                                                    <option selected>Selecciona la unidad correspondiente</option>
                                                    <?php foreach ($unidades as $unidad): ?>
                                                        <option value="<?php echo $unidad['id']; ?>">
                                                            <?php echo $unidad['id'] . ' - ' . $unidad['nombre'] . ' - ' . $unidad['unidad']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-sm-6">
                                                <label for="exampleInputPassword">Contraseña</label>
                                                <input type="password" class="form-control" id="exampleInputPassword"
                                                    name="password" placeholder="Contraseña" autocomplete="off">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="exampleRepeatPassword">Repetir Contraseña</label>
                                                <input type="password" class="form-control" id="exampleRepeatPassword"
                                                    name="repeatPassword" placeholder="Repetir Contraseña"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Registrar
                                            Cuenta</button>
                                    </form>

                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sistema Facturas 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->


    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Seguro que quieres salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión
                    actual</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="php/logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="ajax/jquery.js"></script>
    <script src="ajax/ajaxDisponibilidad.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>