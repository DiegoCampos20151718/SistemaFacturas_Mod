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
    $stmt = $connecction->prepare("SELECT id,oficina FROM oficinas WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['oficina']); // "i" indica que es un entero

    $stmt->execute();
    $result = $stmt->get_result();

    $ofCor = "";
    $idof = "";

    // Verifica si hay resultados y guarda el nombre en una variable
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ofCor = $row['oficina'];
        $idof = $row['id'];
    } else {
        $ofCor = "No se encontró la unidad";
    }

    $stmt->close(); // Cerrar la declaración preparada

} else {
    echo "No se ha establecido una oficina en la sesión.";
}

$sql = "SELECT id, oficina FROM oficinas";
$result = $connecction->query($sql);
$oficinas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $oficinas[] = $row;
    }
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

    <title>Registro Facturas</title>
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

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="img/imss-logo-gret.png" />
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
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto d-flex justify-content-start">
                        <li class="nav-item"
                            style="margin-right: 5rem; margin-top: 0.5rem; position: absolute; left: 0;">
                            <span class="mr-2 d-none d-lg-inline text-gray-600"
                                style="font-size: 1.5rem; font-weight: bold;">
                                Unidad: <?php echo $uniCor; ?> (<?php echo $uniCor2; ?>)
                            </span>
                        </li>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php
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
                                    ?> </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Registro de Factura</h6>
                        </div>
                        <div class="card-body">
                            <form id="formulario-registro-factura" enctype="multipart/form-data">
                                <!-- Datos del proveedor y contrato -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="proveedor" class="form-label">Proveedor:</label>
                                            <select class="form-select" id="proveedor" name="proveedor" required>
                                                <option selected>Selecciona el número de proveedor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="contrato" class="form-label">Contrato:</label>
                                            <select class="form-select" id="contrato" name="contrato" required>
                                                <option selected>Selecciona un contrato</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
    <div class="form-group">
        <label for="oficina" class="form-label">ID Oficina:</label>

        <?php if ($_SESSION["rol"] == 1): ?>
            <!-- Mostrar la lista desplegable si rol es 1 -->
            <select class="form-select" id="oficinas" name="oficina" required>
                <option selected>Selecciona la oficina correspondiente</option>
                <?php foreach ($oficinas as $oficina): ?>
                    <option value="<?php echo $oficina['id']; ?>">
                        <?php echo $oficina['id'] . ' - ' . $oficina['oficina']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <!-- Mostrar el campo de texto en caso contrario -->
            <input disabled type="text" class="form-control" id="oficina" name="oficina"
                   value="<?php echo $idof, " - ", $ofCor; ?>" required>
        <?php endif; ?>
    </div>
</div>


                                </div>

                                <!-- Información de la factura -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="no-factura" class="form-label">No. de Factura:</label>
                                            <input type="text" class="form-control" id="no-factura" name="no-factura"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fecha-registro" class="form-label">Fecha de Registro:</label>
                                            <input type="date" class="form-control" id="fecha-registro"
                                                name="fecha-registro" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fecha-factura" class="form-label">Fecha de Factura:</label>
                                            <input type="date" class="form-control" id="fecha-factura"
                                                name="fecha-factura" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Concepto y Observaciones -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="concepto" class="form-label">Concepto:</label>
                                            <input type="text" class="form-control" id="concepto" name="concepto">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="observaciones" class="form-label">Observaciones:</label>
                                            <textarea class="form-control" id="observaciones" name="observaciones"
                                                rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <label class="form-label">Tipo:</label>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo"
                                                    id="tipo-contrato" value="C" checked>
                                                <label class="form-check-label" for="tipo-contrato"
                                                    style="position: relative; top: -5px;">Contrato</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo"
                                                    id="tipo-disponible" value="D">
                                                <label class="form-check-label" for="tipo-disponible"
                                                    style="position: relative; top: -5px;">Disponible</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        </div>

                        <!-- Codificación y Montos -->
                        <div class="row">
                            <div class="col-md-3" style="margin-left: 15px;">
                                <div class="form-group">
                                    <label for="codificacion-primaria" class="form-label">Codificación Primaria:</label>
                                    <select class="form-select" id="codificacion-primaria" name="codificacion-primaria"
                                        required>
                                        <option selected>Selecciona una codificación</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="monto-primario" class="form-label">Monto Primario:</label>
                                    <input type="number" class="form-control" id="monto-primario" name="monto-primario"
                                        placeholder="Monto Primario" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <button type="button" class="btn btn-success" id="agregar-monto"
                                    style="margin-top: 15px;">Agregar Monto</button>
                            </div>
                        </div>


                        <!-- Tabla de Montos -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabla-montos">
                                <thead>
                                    <tr>
                                        <th>Cuenta</th>
                                        <th>U. DEI</th>
                                        <th>C.C.</th>
                                        <th>Monto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-left: 15px;">
                                <div class="mb-3">
                                    <label for="codificacion" class="form-label">Codificación:</label>
                                    <select class="form-select" id="codificacion" name="codificacion">
                                        <option selected>Selecciona una codificacion</option>

                                    </select>


                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Monto:</label>
                                    <input type="number" class="form-control" id="monto" name="monto"
                                        placeholder="Monto de apoyo" step="0.01" autocomplete="off">

                                </div>

                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <button type="button" class="btn btn-success" id="agregar-monto"
                                    style="margin-top: 15px;">Agregar Monto</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <table class="table table-bordered" id="tabla-apoyos">
                                <thead>
                                    <tr>
                                        <th>Cuenta</th>
                                        <th>U. DEI.</th>
                                        <th>C.C.</th>
                                        <th>Monto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>

                        <!-- Copia de la factura -->
                        <div class="row">
                            <div class="col-md-5" style="margin-left: 15px;">
                                <div class="form-group">
                                    <label for="copia-factura" class="form-label">Copia de Factura:</label>
                                    <input type="file" class="form-control" id="copia-factura" name="copia-factura"
                                        accept="application/pdf" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success" style="margin-top: 30px;">Guardar
                                    Factura</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="sticky-footer bg-white" style="padding: 5px 0; height: auto;">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Sistema Facturas 2024</span>
            </div>
        </div>
    </footer>

    <!-- End of Main Content -->

    <!-- Footer -->


    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- End of Footer -->
    <!-- Scroll to Top Button-->

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="ajax/ajaxFacturas.js"></script>
    <script src="ajax/jquery.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>



</body>

</html>