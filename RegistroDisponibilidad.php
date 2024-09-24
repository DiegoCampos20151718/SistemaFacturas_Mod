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
    <link rel="shortcut icon" href="img/imss-logo-gret.png"/>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
                                echo $_SESSION["nombre"]." ". $_SESSION["apellido"] ;
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
                    
                    <!-- Modal -->
                    <div class="modal fade" id="nuevoCodigoModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  aria-labelledby="nuevoCodigoModalLabel" aria-hidden="true">
                        <div class="modal-dialog" >
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="nuevoCodigoModalLabel">Nueva Codificación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="nuevoCodigoForm">
                                  <div class="form-group">
                                    <label for="cuentaInput">Cuenta</label>
                                    <input type="text" class="form-control" id="cuentaInput" required maxlength="8" autocomplete="off">
                                  </div>
                                  <div class="form-group">
                                    <label for="udeiInput">UDEI</label>
                                    <input type="text" class="form-control" id="udeiInput" required maxlength="6" autocomplete="off">
                                  </div>
                                  <div class="form-group">
                                    <label for="ccInput">CC</label>
                                    <input type="text" class="form-control" id="ccInput" required maxlength="6" autocomplete="off">
                                  </div>
                                  <div class="form-group">
                                    <label for="anio">Año</label>
                                    <select class="form-select" aria-label="Default select example" id="anioInput" name="anio" required ></select>
                                    <script>
                                        function generarOpcionesAnos(anoInicial, cantidadAnos) {
                                            const selectAno = document.getElementById('anioInput');
                                                                    
                                            selectAno.innerHTML = '';

                                            const opcionVacia = document.createElement('option');
                                            opcionVacia.value = '';
                                            opcionVacia.text = 'Seleccione un año';
                                            selectAno.add(opcionVacia);
                                        
                                        
                                            for (let i = 0; i < cantidadAnos; i++) {
                                                const ano = anoInicial + i;
                                                const opcion = document.createElement('option');
                                                opcion.value = ano;
                                                opcion.text = ano;
                                                selectAno.add(opcion);
                                            }
                                        }
                                        
                                        window.onload = function() {
                                            const fechaActual = new Date();
                                            const anoActual = fechaActual.getFullYear();
                                            generarOpcionesAnos(anoActual, 2); 
                                        };
                                    </script>
                                  </div>
                                  <button type="submit" class="btn btn-primary" id="guardarCodigoBtn">Guardar</button>
                                </form>
                              </div>
                        </div>
                        </div>
                    </div>
                    <!-- Modal para editar datos -->
                    <div class="modal fade" id="editarDatosModal" tabindex="-1" aria-labelledby="editarDatosModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarDatosModalLabel">Editar Datos</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editarDatosForm">
                                        <div class="form-group">
                                            <label for="editarImporteDefInput">Importe Def</label>
                                            <input type="number" class="form-control" id="editarImporteDefInput" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="editarCargosInput">Cargos</label>
                                            <input type="number" class="form-control" id="editarCargosInput" required>
                                        </div>
                                        <input type="hidden" id="editarMesInput">
                                        <input type="hidden" id="editarAnioInput">
                                        <input type="hidden" id="editarCodificacionInput">
                                        <button type="submit" class="btn btn-primary" id="guardarEdicionBtn">Guardar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                   <!--TABLA-->
                   <div class="container mt-5">
                    <h1>Registro de disponibilidad</h1>
                    <div class="row">
                      <div class="col-md-4">
                        <select class="form-select" aria-label="" name="codificacion" id="codigoInput">
                          <option value="">Selecciona una opcion</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <select class="form-select" aria-label="" name="year" id="yearInput">
                          <option value="">Selecciona año</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevoCodigoModal">
                          <i class="bi bi-plus-circle">Agregar codificacion</i>
                        </button>
                      </div>
                      <div class="col-md-3">
                        <button type="button" class="btn btn-danger" id="eliminar-codificacion-btn">
                          <i class="bi bi-trash">Eliminar codificacion</i>
                        </button>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover mt-3 text-center w-100">
                        <thead>
                          <tr>
                            <th>Mes</th>
                            <th>Año</th>
                            <th>Importe Def</th>
                            <th>Cargos</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                <!--TABLA FIN-->

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
    <script src="ajax/jquery.js"></script>
    <script src="ajax/ajaxDisponibilidad.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>