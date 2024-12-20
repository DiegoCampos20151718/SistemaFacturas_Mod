<?php
include("php/check_session.php");
include("php/database.php");


// Contabilizar facturas registradas este año
$year = date("Y");
$stmt_facturas = $connecction->prepare("SELECT COUNT(*) as total_facturas FROM facturas WHERE YEAR(FechaDeFactura) = ?");
$stmt_facturas->bind_param("i", $year);
$stmt_facturas->execute();
$result_facturas = $stmt_facturas->get_result();
$total_facturas = $result_facturas->fetch_assoc()['total_facturas'];
$stmt_facturas->close();

// Contabilizar contratos vigentes
$stmt_contratos = $connecction->prepare("SELECT COUNT(*) as total_contratos FROM contratos WHERE VigenciaFin >= CURDATE()");
$stmt_contratos->execute();
$result_contratos = $stmt_contratos->get_result();
$total_contratos = $result_contratos->fetch_assoc()['total_contratos'];
$stmt_contratos->close();

// Contabilizar proveedores activos
$stmt_proveedores = $connecction->prepare("SELECT COUNT(*) as total_proveedores FROM proveedores");
$stmt_proveedores->execute();
$result_proveedores = $stmt_proveedores->get_result();
$total_proveedores = $result_proveedores->fetch_assoc()['total_proveedores'];
$stmt_proveedores->close();

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

    <title>Graficas</title>
    <!-- SweetAlert2 CSS local -->
    <link rel="stylesheet" href="libs/SweetAlert2/sweetalert2.min.css">
    <!-- SweetAlert2 JS local -->
    <script src="libs/SweetAlert2/sweetalert2.min.js"></script>

    <!-- Custom fonts for this template-->
    <link rel="shortcut icon" href="img/imss-logo-gret.png" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

</head>
<style>
    .card-container {
        display: flex;
        justify-content: space-around; /* Distribuye espacio entre las tarjetas */
        flex-wrap: wrap; /* Permite que las tarjetas se muevan a una nueva línea si no hay espacio */
        gap: 20px; /* Espacio entre las tarjetas */
        margin-top: 20px;
    }

    .card {
        flex: 1 1 calc(33.333% - 40px); /* Ocupa un tercio del ancho disponible con un pequeño margen */
        max-width: 32%; /* Limita el ancho máximo al 32% para crear márgenes naturales */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #ffffff;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        text-align: center;
        padding: 20px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333333;
    }

    .card-icon {
        font-size: 3rem;
        color: #4e73df;
        margin-bottom: 15px;
    }

    .chart-container {
        width: 100%;
        margin: auto;
        padding: 10px 0;
    }

    canvas {
        width: 100% !important;
        height: auto !important;
        max-height: 400px;
    }

    /* Para pantallas pequeñas, asegúrate de que las tarjetas se ajusten correctamente */
    @media (max-width: 768px) {
        .card {
            flex: 1 1 100%; /* Las tarjetas ocupan el 100% del ancho disponible en pantallas pequeñas */
            max-width: 100%;
        }
    }
</style>
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
            <?php if ($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2): ?>
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
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto d-flex justify-content-start">
                        <li class="nav-item"
                            style="margin-right: 5rem; margin-top: 0.5rem; position: absolute; left: 0;">
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
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="container my-5">
                        <h1 class="text-center mb-5">Resumen de Actividades</h1>
                        <div class="row stats-grid">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <h5 class="card-title">Facturas registradas</h5>
                                        <p class="card-text"><?php echo $total_facturas; ?> facturas este año</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-icon">
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                        <h5 class="card-title">Contratos vigentes</h5>
                                        <p class="card-text"><?php echo $total_contratos; ?> contratos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-icon">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <h5 class="card-title">Proveedores activos</h5>
                                        <p class="card-text"><?php echo $total_proveedores; ?> proveedores</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container my-5">
                        <h1>Graficas</h1>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codificacion">Seleciona una codificacion:</label>
                                    <select class="form-control" id="codificacion">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year">Seleciona el año:</label>
                                    <select class="form-control" id="year">
                                        <option value="">Selecciona una opcion</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <canvas id="myChart" style="display: none;"></canvas>
                    </div>
                    <div class="card-container">
    <div class="card">
        <div class="card-body">
            <div class="card-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <h5 class="card-title">Facturas por Mes</h5>
            <div class="chart-container">
                <canvas id="facturasLineChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h5 class="card-title">Facturas por Contrato</h5>
            <div class="chart-container">
                <canvas id="facturasBarChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h5 class="card-title">Gastos vs Monto Máximo</h5>
            <div class="chart-container">
                <canvas id="contratoPieChart"></canvas>
            </div>
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

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="ajax/jquery.js"></script>
    <script src="ajax/ajaxGrafica.js"></script>


    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajax({
            url: 'php/grafica/Facturas-Mes.php', // Cambia esto por la ruta a tu API
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log(response); // Verifica la respuesta
                // 1. Gráfica de líneas
                var facturasPorMes = response.facturas_por_mes || [];
                var facturasPorMesCompleto = new Array(12).fill(0);
                facturasPorMes.forEach((total, index) => {
                    facturasPorMesCompleto[index] = total || 0; // Asegúrate de que no sea undefined
                });

                var facturasLineCtx = document.getElementById('facturasLineChart').getContext('2d');
                var facturasLineChart = new Chart(facturasLineCtx, {
                    type: 'line',
                    data: {
                        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        datasets: [{
                            label: 'Facturas Pagadas',
                            data: facturasPorMesCompleto,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 2. Gráfica de barras
                var facturasPorContrato = response.facturas_por_contrato || [];
                var contratos = facturasPorContrato.map(item => item.contrato);
                var totals = facturasPorContrato.map(item => item.total);

                var facturasBarCtx = document.getElementById('facturasBarChart').getContext('2d');
                var facturasBarChart = new Chart(facturasBarCtx, {
                    type: 'bar',
                    data: {
                        labels: contratos,
                        datasets: [{
                            label: 'Facturas Pagadas',
                            data: totals,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 3. Gráfica de pastel
                var gastosPorContrato = response.gastos_contrato || [];
                var nombres = gastosPorContrato.map(item => item.nombre);
                var avances = gastosPorContrato.map(item => (item.total_gastado / item.monto_maximo) * 100);

                var contratoPieCtx = document.getElementById('contratoPieChart').getContext('2d');
                var contratoPieChart = new Chart(contratoPieCtx, {
                    type: 'pie',
                    data: {
                        labels: nombres,
                        datasets: [{
                            label: 'Avance en porcentaje',
                            data: avances,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error en la petición:', textStatus, errorThrown);
            }
        });
    </script>

</body>

</html>