$(function() {
    //Función para cargar las codificaciones
    function loadCodificaciones() {
        $.ajax({
            url: "php/disponibilidad/get_codificaciones.php",
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#codigoInput').empty().append('<option value="">Selecciona una opción</option>');
                $.each(data, function(index, codificacion) {
                    $('#codigoInput').append('<option value="' + codificacion + '">' + codificacion + '</option>');
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al cargar las codificaciones',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }

    // Función para cargar los años según la codificación seleccionada
    function loadYears(codificacion) {
        $.ajax({
            url: "php/disponibilidad/get_years.php",
            type: "GET",
            data: { codificacion: codificacion },
            dataType: "json",
            success: function(data) {
                $('#yearInput').empty().append('<option value="">Selecciona año</option>');
                $.each(data, function(index, year) {
                    $('#yearInput').append('<option value="' + year + '">' + year + '</option>');
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al cargar los años',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }

    // Función para cargar los datos en la tabla
    function loadData(codificacion, year) {
        $.ajax({
            url: "php/disponibilidad/obtener_datos.php",
            type: "GET",
            data: { codificacion: codificacion, year: year },
            dataType: "json",
            success: function(data) {
                var $table = $("table tbody");
                $table.empty();
                $.each(data, function(index, row) {
                    var acciones = `
                        <button class="btn btn-primary btn-sm editar-btn" data-mes="${row.mes}" data-anio="${row.anio}" data-codificacion="${row.codificacion}" data-importedef="${row.importeDef}" data-cargos="${row.cargos}"><i class="bi bi-pencil-square"></i></button>
                    `;
                    $table.append(`
                        <tr>
                            <td>${row.mes}</td>
                            <td>${row.anio}</td>
                            <td>${row.importeDef}</td>
                            <td>${row.cargos}</td>
                            <td>${acciones}</td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al cargar los datos',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }

    // Cargar las codificaciones al cargar la página
    loadCodificaciones();

    // Manejar el cambio de selección en el select de codificación
    $("#codigoInput").on("change", function() {
        var codificacion = $(this).val();
        if (codificacion) {
            loadYears(codificacion);
        } else {
            $('#yearInput').empty().append('<option value="">Selecciona año</option>');
            loadData('', ''); 
        }
    });

    // Manejar el cambio de selección en el select de año
    $("#yearInput").on("change", function() {
        var codificacion = $("#codigoInput").val();
        var year = $(this).val();
        loadData(codificacion, year);
    });
    //Cierre del modal
    $("#nuevoCodigoModal").on("hidden.bs.modal", function() {
        $("#nuevoCodigoForm")[0].reset();
    });
    //Agregar codificacion
    $("#nuevoCodigoForm").on("submit", function(event) {
        event.preventDefault();
    
        var cuenta = $("#cuentaInput").val();
        var udei = $("#udeiInput").val();
        var cc = $("#ccInput").val();
        var anio = $("#anioInput").val();
    
        if (cuenta === "" || udei === "" || cc === "" || anio === "") {
              Swal.fire({
                 title: 'Advertencia',
                 text: 'Todos los campos son obligatorios',
                   icon: 'warning',
                  confirmButtonText: 'Aceptar'
              });
            return;
          }
    
         $.ajax({
              url: "php/disponibilidad/insertar_codificacion.php",
              type: "POST",
              data: {
                cuenta: cuenta,
                udei: udei,
                cc: cc,
                anio: anio
             },
             dataType: "json",
             success: function(response) {
                 if (response.success) {
                     Swal.fire({
                         title: 'Éxito',
                           text: response.message,
                           icon: 'success',
                           confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();  // Recargar la página después de confirmar
                        }
                      });                   
                      $("#nuevoCodigoForm")[0].reset();
                   } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                      });
                    }
             },
             error: function(xhr, status, error) {
                 Swal.fire({
                     title: 'Error',
                     text: 'Error al insertar la codificación',
                   icon: 'error',
                   confirmButtonText: 'Aceptar'
                });
             }
        });
    });

    // Manejar el evento clic en el botón "Editar"
    $("table tbody").on("click", ".editar-btn", function() {
        var mes = $(this).data("mes");
        var anio = $(this).data("anio");
        var codificacion = $(this).data("codificacion");
        var importeDef = $(this).data("importedef");
        var cargos = $(this).data("cargos");

        // Mostrar el modal y rellenar los campos con los datos actuales
        $("#editarDatosModal").modal("show");
        $("#editarImporteDefInput").val(importeDef);
        $("#editarCargosInput").val(cargos);
        $("#editarMesInput").val(mes);
        $("#editarAnioInput").val(anio);
        $("#editarCodificacionInput").val(codificacion);
    });

    // Manejar el envío del formulario de edición
    $("#editarDatosForm").on("submit", function(e) {
        e.preventDefault(); // Evitar el envío del formulario

        // Obtener los valores del formulario
        var importeDef = $("#editarImporteDefInput").val();
        var cargos = $("#editarCargosInput").val();
        var mes = $("#editarMesInput").val();
        var anio = $("#editarAnioInput").val();
        var codificacion = $("#editarCodificacionInput").val();

        // Enviar los datos al servidor mediante AJAX
        $.ajax({
            url: "php/disponibilidad/editar_datos.php",
            type: "POST",
            data: {
                mes: mes,
                anio: anio,
                codificacion: codificacion,
                importeDef: importeDef,
                cargos: cargos
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Cerrar el modal
                    $("#editarDatosModal").modal("hide");

                    // Actualizar la fila en la tabla
                    var $row = $(`table tbody tr td:contains(${codificacion})`).parent();
                    $row.find("td:eq(3)").text(importeDef);
                    $row.find("td:eq(4)").text(cargos);
                    loadData(codificacion);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al editar los datos',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Manejar el cierre del modal
    $("#editarDatosModal").on("hidden.bs.modal", function() {
        $("#editarDatosForm")[0].reset();
    });

    // Manejar el evento clic en el botón "Eliminar"
    $("#eliminar-codificacion-btn").on("click", function() {
        var codificacion = $("#codigoInput").val();
        var anio = $("#yearInput").val();
    
        if (codificacion === "" || anio === "") {
            Swal.fire({
                title: 'Advertencia',
                text: 'Por favor, selecciona una codificación y un año',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
    
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Estás seguro de eliminar esta codificación y todos sus registros del año seleccionado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "php/disponibilidad/eliminar_datos.php",
                    type: "POST",
                    data: {
                        codificacion: codificacion,
                        anio: anio
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Eliminada',
                                text: 'Todos los registros relacionados con la codificación y el año seleccionado han sido eliminados.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                           
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al eliminar la codificación',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    });    
});
