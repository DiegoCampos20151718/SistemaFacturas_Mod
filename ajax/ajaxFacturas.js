$(document).ready(function() {
    // Cargar opciones de proveedores
    $.ajax({
        url: 'php/facturas/cargar_proveedores.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $.each(data, function(index, proveedor) {
                $('#proveedor').append('<option value="' + proveedor.NoProveedor + '">' + proveedor.NomProveedor + '</option>');
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los proveedores.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    // Cargar opciones de contratos
    $('#proveedor').change(function() {
        var proveedorSeleccionado = $(this).val();
        $.ajax({
            url: 'php/facturas/cargar_contratos.php',
            type: 'GET',
            data: { proveedor: proveedorSeleccionado },
            dataType: 'json',
            success: function(data) {
                $('#contrato').empty(); 
                $('#contrato').append('<option value="">Selecciona un contrato</option>'); 
    
                $.each(data, function(index, contrato) {
                    $('#contrato').append('<option value="' + contrato.NoContrato + '">' + contrato.NoContrato + '</option>');
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al cargar los contratos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
    

    // Cargar opciones de codificaciones
    $.ajax({
        url: 'php/facturas/cargar_codificaciones.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $.each(data, function(index, codificacion) {
                $('#codificacion').append('<option value="' + codificacion.codificacion + '">' + codificacion.codificacion + '</option>');
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar las codificaciones.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    function validarMonto(codificacion, monto, fechaRegistro, callback) {
       
        $.ajax({
            url: 'php/facturas/validar_montos.php',
            type: 'POST',
            dataType: 'json',
            data: {
                codificacion: codificacion,
                fecha_registro: fechaRegistro
            },
            success: function(response) {
                if (response.success) {
                    var disponibleAcumulado = parseFloat(response.disponibleAcumulado);
                    var montoParseado = parseFloat(monto);
   
                    if (montoParseado > disponibleAcumulado) {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'El monto ingresado supera el disponible acumulado para la codificación y fecha de registro.',
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        });
                        callback(false);
                    } else {
                        callback(true);
                    }
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.mensaje,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    callback(false);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al validar el monto.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                callback(false);
            }
        });
        
        
    }
    function obtenerCamposDesdecodificacion(codificacion) {
        var cuenta = codificacion.slice(0, 8);
        var udei = codificacion.slice(8, 13);
        var cc = codificacion.slice(13);
        return { cuenta: cuenta, udei: udei, cc: cc };
    }

   //Accion al dar click agregar monto
    $('#agregar-monto').click(function() {
        var codificacionP = $('#codificacion-primaria').val();
        var montoP = $('#monto-primario').val();
        var fechaRegistro = $('#fecha-registro').val(); 
    
      
        if ($('#tabla-montos tbody tr').length > 0) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Ya se ha ingresado el monto primario.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
    
        
            if (codificacionP === null || codificacionP === 'Selecciona una codificacion' || codificacionP === '') {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'Por favor selecciona una codificación.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
    
            if (isNaN(parseFloat(montoP))) {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'Por favor ingresa un número válido en el campo de monto.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            
          validarMonto(codificacionP, montoP, fechaRegistro, function(validado) {
            if (!validado) {
                return;
            }
    
            var camposDesdeCodificacionP = obtenerCamposDesdecodificacion(codificacionP);
    
            var fila = '<tr>' +
                '<td><input type="text" class="form-control cuentaP" name="cuentaP[]" value="' + camposDesdeCodificacionP.cuenta + '" readonly></td>' +
                '<td><input type="text" class="form-control udeiP" name="udeiP[]" value="' + camposDesdeCodificacionP.udei + '" readonly></td>' +
                '<td><input type="text" class="form-control ccP" name="ccP[]" value="' + camposDesdeCodificacionP.cc + '" readonly></td>' +
                '<td><input type="number" class="form-control montoP" name="montoP[]" value="' + montoP + '" step="0.01" readonly></td>' +
                '<td><button type="button" class="btn btn-danger eliminar-fila">Eliminar</button></td>' +
                '</tr>';
            $('#tabla-montos tbody').append(fila);
        });
    });
    
    //Accion al dar click agregar monto
    $('#agregar-apoyo').click(function() {
        var codificacion = $('#codificacion').val();
        var monto = $('#monto').val();
        var fechaRegistro = $('#fecha-registro').val(); 

        if ($('#tabla-apoyos tbody tr').length > 0) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Ya se ha ingresado el monto de apoyo.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
    
        if (codificacion === null || codificacion === 'Selecciona una codificacion' || codificacion === '') {
            Swal.fire({
                title: 'Advertencia',
                text: 'Por favor selecciona una codificación.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
     
        if (isNaN(parseFloat(monto))) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Por favor ingresa un número válido en el campo de monto.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
    
    
        validarMonto(codificacion, monto, fechaRegistro, function(validado) {
            if (!validado) {
                return; 
            }

    
            // Si no se supera, agregar la fila a la tabla de apoyos
            var camposDesdeCodificacion = obtenerCamposDesdecodificacion(codificacion);
    
            var fila = '<tr>' +
                '<td><input type="text" class="form-control cuenta" name="cuenta[]" value="' + camposDesdeCodificacion.cuenta + '" readonly></td>' +
                '<td><input type="text" class="form-control udei" name="udei[]" value="' + camposDesdeCodificacion.udei + '" readonly></td>' +
                '<td><input type="text" class="form-control cc" name="cc[]" value="' + camposDesdeCodificacion.cc + '" readonly></td>' +
                '<td><input type="number" class="form-control monto" name="monto[]" value="' + monto + '" step="0.01" readonly></td>' +
                '<td><button type="button" class="btn btn-danger eliminar-fila">Eliminar</button></td>' +
                '</tr>';
            $('#tabla-apoyos tbody').append(fila);
        });
    });
    


    // Eliminar fila de apoyo
    $(document).on('click', '.eliminar-fila', function() {
        $(this).closest('tr').remove();
    });

    $(document).ready(function() {
        // Auto llenar la fecha de registro con la fecha actual
        $('#fecha-registro').val(new Date().toISOString().split('T')[0]);
    
        // Verificar la vigencia del contrato al seleccionarlo
        $('#contrato').change(function() {
            var contratoId = $(this).val();
            var fechaRegistro = $('#fecha-registro').val();
    
            if (contratoId) {
                $.ajax({
                    url: 'php/facturas/verificar_vigencia_contrato.php',
                    type: 'POST',
                    data: {
                        contrato: contratoId,
                        fecha_registro: fechaRegistro
                    },
                    success: function(response) {
                        var responseJSON = JSON.parse(response);
                        if (responseJSON.valid === false) {
                            Swal.fire({
                                title: 'Error',
                                text: 'El contrato seleccionado no está vigente para la fecha de registro.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            $('#contrato').val('').focus();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al verificar la vigencia del contrato.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    
        // Manejar el envío del formulario
        $('#formulario-registro-factura').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'php/facturas/guardar_factura.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.indexOf('Error') === -1) {
                        $('#formulario-registro-factura')[0].reset();
                        $('#tabla-apoyos tbody').empty();
                        $('#tabla-montos tbody').empty();
                        Swal.fire({
                            title: 'Éxito',
                            text: 'La factura se ha guardado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al guardar la factura.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    });
    
    

    // Cargar codificaciones primarias
    $.ajax({
        url: 'php/facturas/cargar_codificaciones_primarias.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $.each(data, function(index, codificacion) {
                $('#codificacion-primaria').append('<option value="' + codificacion.codificacion + '">' + codificacion.codificacion + '</option>');
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar las codificaciones primarias.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    cargarTablaFacturas();

    // Función para cargar la tabla
    function cargarTablaFacturas() {
        $.get('php/facturas/cargar_facturas.php', function(data) {
            $('#facturasTable').html(data);
            var dataTable = $('#Data_Table').DataTable({
                language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });

            // Asignar el evento click a los botones "Detalle Monto" utilizando delegación de eventos
            $('#facturasTable').on('click', '.btn-detalle-monto', function() {
                var factura = $(this).data('factura');
                $.ajax({
                    url: 'php/facturas/Detalle_monto.php',
                    type: 'POST',
                    data: { factura: factura },
                    success: function(response) {
                        $('#detalleModal .modal-body').html(response);
                        $('#detalleModal').modal('show');
                    }
                });
            });

            asignarEventoVerFactura();
        }).fail(function() {
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar la tabla de facturas.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    }

    // Función para asignar el evento clic al botón "Ver Factura"
    function asignarEventoVerFactura() {
        $('#facturasTable').on('click', '.btn-ver-factura', function() {
            var noFactura = $(this).data('factura');
            $.ajax({
                url: 'php/facturas/ver_factura.php',
                type: 'GET',
                data: { factura: noFactura },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data, textStatus, jqXHR) {
                    var blob = new Blob([data], { type: jqXHR.getResponseHeader('Content-Type') });
                    var url = window.URL.createObjectURL(blob);
                    window.open(url, '_blank');
                    window.URL.revokeObjectURL(url);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var errorMessage = 'Error al cargar la factura: ';
                    if (jqXHR.status === 404) {
                        errorMessage += 'Factura no encontrada.';
                    } else {
                        errorMessage += errorThrown;
                    }
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    }

    // Asignar el evento click al botón "Eliminar Factura" utilizando delegación de eventos
    $('#facturasTable').on('click', '.btn-eliminar-factura', function() {
        var noFactura = $(this).data('factura');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Estás seguro de eliminar esta factura?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'php/facturas/eliminar_factura.php',
                    type: 'POST',
                    data: { noFactura: noFactura },
                    success: function(response) {
                        Swal.fire({
                            title: 'Eliminada',
                            text: response,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        cargarTablaFacturas();  // Recargar la tabla después de eliminar la factura
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al eliminar la factura.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    });
});
