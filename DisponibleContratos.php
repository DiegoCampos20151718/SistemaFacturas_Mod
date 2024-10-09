<?php
include("php/check_session.php");
include("php/database.php");

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

$connecction->close(); // Cerrar la conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Disponible Contratos</title>
    <!-- SweetAlert2 CSS local -->
    <link rel="stylesheet" href="libs/SweetAlert2/sweetalert2.min.css">
    <!-- SweetAlert2 JS local -->
    <script src="libs/SweetAlert2/sweetalert2.min.js"></script>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Icons boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom Tables-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="img/imss-logo-gret.png"/>
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

            <!-- Nav Item - Graficas -->
            <li class="nav-item">
                <a class="nav-link" href="Graficas.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Graficas</span></a>
            </li>

            <!-- Nav Item - DisponibleContratos -->
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
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

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
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Disponibilidad en contratos</h1>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="codificacion-filter" class="form-label" name="codificacion">Filtrar por Codificación:</label>
                                <select class="form-select" id="codificacion-filter">
                                    <option value="">Selecciona una codificacion</option>
                                    <!-- Opciones serán llenadas dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                            <label for="codificacion-filter" class="form-label" name="codificacion">Año:</label>
                            <select id="year-filter" class="form-select">
                                <option value="">Selecciona año</option>
                            </select>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                
                                <table class="table table-bordered table-hover mt-3 text-center w-100">
                                    <thead>
                                        <tr>
                                            <th>Codificación</th>
                                            <th>Periodo</th>
                                            <th>Importe Def</th>
                                            <th>Número de Cargos</th>
                                            <th>Importe Cargos</th>
                                            <th>Disponible Acumulado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-table">
                                        <!-- Filas de datos aquí -->
                                    </tbody>
                                </table>
                              </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

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
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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
                <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual</div>
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

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="ajax/ajaxDisponibleContrat.js"></script>
    <script src="ajax/jquery.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
</html>