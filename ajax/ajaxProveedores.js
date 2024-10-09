$(function() {
    $("#task-result").hide();
    
    fetchTasks();
    let edit = false;

    //Agregar contrato o editar
    $("#proveedor-form").submit(e => {
        e.preventDefault();
        const modo = edit ? "editar" : "agregar";
        const postData = {
            NoProveedor: $("#NoProveedor").val(),
            NomProveedor: $("#NomProveedor").val().toUpperCase(),
            modo: modo
        }
        
        const url = modo === "agregar" ? "php/proveedores/agregar-proveedor.php" : "php/proveedores/editar-proveedor.php";
        
        $.ajax({
            url: url,
            data: postData,
            type: "POST",
            dataType: "json",
            success: function(response) {
                if (response.hasOwnProperty("success")) {
                    fetchTasks();
                    $("#proveedor-form").trigger("reset");
                    $('#provModal').modal('hide');
                    Swal.fire('¡Éxito!', response.success, 'success');
                } else if (response.hasOwnProperty("error")) {
                    console.error(response.error);
                    Swal.fire('Error', response.error, 'error');
                } else {
                    console.error("Respuesta del servidor inesperada");
                    Swal.fire('Error', "Se produjo un error inesperado al procesar la solicitud. Por favor, inténtelo nuevamente.", 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire('Error', "Se produjo un error al enviar la solicitud. Por favor, inténtelo nuevamente.", 'error');
            }
        });
    });

    $("#agregarP").click(() => {
        $("#proveedor-form").trigger("reset");
        $("#NoProveedor").prop("disabled", false);
        edit = false;
    });

    //Mostrar datos en la tabla
    function fetchTasks() {
        $.ajax({
            url: "php/proveedores/tabla-proveedores.php",
            type: "GET",
            success: function(response) {
                const tasks = JSON.parse(response);

                let template = ``;
                tasks.forEach(task => {
                    template += `
                    <tr taskId="${task.NoProveedor}">
                        <td>${task.NoProveedor}</td>
                        <td>${task.NomProveedor}</td>
                       ${userRole == 1 || userRole == 2 ? ` <td>
                            ${userRole == 1 ? `<button class="btn btn-danger text-center task-delete"><i class="bi bi-trash"></i></button>` : ''}
                           ${userRole == 1 || userRole == 2 ? ` <button class="btn btn-primary text-center task-item"><i class="bi bi-pencil-square"></i></button>` : ''}
                        </td>` : ''}
                    </tr>
                    `;
                });
                $("#proveedores").html(template);
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los proveedores: ", error);
            }
        });
    }

    //ELIMINAR
    $(document).on("click", ".task-delete", function() {
        const element = $(this).closest('tr');
        const NoProveedor = $(element).attr("taskId");
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("php/proveedores/eliminar-proveedor.php", { NoProveedor }, () => {
                    fetchTasks();
                    Swal.fire('¡Eliminado!', 'El proveedor ha sido eliminado.', 'success');
                });
            }
        });
    });

    // Evento para hacer clic en un elemento para editar el contrato
    $(document).on("click", ".task-item", function() {
        const element = $(this).closest('tr');
        const NoProveedor = $(element).attr("taskId");

        $("#NoProveedor").prop("disabled", true); 
        $.ajax({
            url: "php/proveedores/obtener-un-proveedor.php",
            data: { NoProveedor },
            type: "POST",
            success: function(response) {
                const contrato = JSON.parse(response);
                $("#NoProveedor").val(contrato.NoProveedor);
                $("#NomProveedor").val(contrato.NomProveedor);
                $("#taskId").val(contrato.NoProveedor);
                $("#provModal").modal("show");
                edit = true;
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire('Error', 'Error al cargar los datos del proveedor', 'error');
            }
        });
    });
});
