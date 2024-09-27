<?php
include("php/check_session.php");
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
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa fa-archive"></i>
                    <span>Registros</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header"> Registros:</h6>
                        <a class="collapse-item" href="RegistroFactura.php">Registrar Factura</a>
                        <a class="collapse-item" href="RegistroDisponibilidad.php">Registro de disponibilidad</a>
                    </div>
                </div>
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
                    <ul class="navbar-nav ml-auto">

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
                                    echo $_SESSION["nombre"] . " " . $_SESSION["apellido"] . "<br>" . rol($_SESSION["rol"]);
                                    ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
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
                <div class="container-fluid">
                        
                        <form id="formulario-registro-factura" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="proveedor" class="form-label">Proveedor:</label>
                                        <select class="form-select" id="proveedor" name="proveedor" required>
                                            <option selected>Selecciona el número de proveedor</option>
                                           
                                        </select>
                                    </div>
    
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="contrato" class="form-label">Contrato:</label>
                                        <select class="form-select" id="contrato" name="contrato" required>
                                            <option selected>Selecciona un contrato</option>
                                            
                                        </select>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="no-factura" class="form-label">No. de Factura:</label>
                                        <input type="text" class="form-control" id="no-factura" name="no-factura" required autocomplete="off">
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="fecha-registro" class="form-label">Fecha de Registro:</label>
                                        <input type="date" class="form-control" id="fecha-registro" name="fecha-registro" required>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="fecha-factura" class="form-label">Fecha de Factura:</label>
                                        <input type="date" class="form-control" id="fecha-factura" name="fecha-factura" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="concepto" class="form-label">Concepto:</label>
                                        <input type="text" class="form-control" id="concepto" name="concepto" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="observaciones" class="form-label">Observaciones:</label>
                                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" autocomplete="off"></textarea>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipo" id="tipo-contrato" value="C" checked>
                                            <label class="form-check-label" for="tipo-contrato">Contrato</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipo" id="tipo-disponible" value="D">
                                            <label class="form-check-label" for="tipo-disponible">Disponible</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Codificacion:</label>
                                        <select class="form-select" id="codificacion-primaria" name="codificacion-primaria" required>
                                            <option selected>Selecciona una codificacion</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Monto:</label>
                                        <input type="number" class="form-control" id="monto-primario" name="monto-primario" placeholder="Monto Primario" step="0.01" required autocomplete="off">

                                    </div>
                                       
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-success mt-5" id="agregar-monto">Agregar Monto</button>

                                    </div>
                                       
                                </div>
                            </div>
                            <div class="mb-3">
                                <table class="table table-bordered" id="tabla-montos">
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
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="codificacion" class="form-label">Codificación:</label>
                                        <select class="form-select" id="codificacion" name="codificacion">
                                            <option selected>Selecciona una codificacion</option>
                                            
                                        </select>
                                        
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Monto:</label>
                                        <input type="number" class="form-control" id="monto" name="monto" placeholder="Monto de apoyo" step="0.01" autocomplete="off">

                                    </div>
                                       
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-success mt-5" id="agregar-apoyo">Agregar Apoyo</button>

                                    </div>
                                       
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
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="copia-factura" class="form-label">Copia de Factura:</label>
                                        <input type="file" class="form-control" id="copia-factura" name="copia-factura" accept="pdf, txt,word" required>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                <button type="submit" class="btn btn-success mt-5" id="guardar-factura">Guardar Factura</button>

                                </div>
                            </div>
                        </form>
                       
                    <!-- Page Heading -->
                    
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