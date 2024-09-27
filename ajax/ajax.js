
$(function(){
    
    $("#task-result").hide();
    
    fetchTasks();
    let edit = false;

    // Agregar contrato o editar
    $("#contrato-form").submit(function (e) {
        e.preventDefault();
        
        const modo = edit ? "editar" : "agregar";
        const MontoMin = parseFloat($("#MontoMin").val());
        const MontoMax = parseFloat($("#MontoMax").val());
        const VigenciaInicio = new Date($("#VigenciaInicio").val());
        const VigenciaFin = new Date($("#VigenciaFin").val());
    
        if (MontoMin > MontoMax) {
            Swal.fire('Error', 'El Monto Mínimo no puede ser mayor al Monto Máximo.', 'error');
            return;
        }
    
        if (VigenciaInicio > VigenciaFin) {
            Swal.fire('Error', 'La Fecha de Inicio no puede ser mayor a la Fecha de Fin.', 'error');
            return;
        }
    
        const postData = {
            NoContrato: $("#NoContrato").val(),
            NoFianza: $("#NoFianza").val(),
            NoProveedor: $("#NoProveedor").val(),
            MontoMin: MontoMin,
            MontoMax: MontoMax,
            VigenciaInicio: $("#VigenciaInicio").val(),
            VigenciaFin: $("#VigenciaFin").val(),
            taskId: $("#taskId").val(),
            modo: modo
        }
    
        const url = modo === "agregar" ? "php/agregar-contrato.php" : "php/editar-contrato.php";
    
        $.ajax({
            url: url,
            data: postData,
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.hasOwnProperty("success")) {
                    fetchTasks();
                    $("#contrato-form").trigger("reset");
                    $('#myModal').modal('hide');
                    Swal.fire('¡Éxito!', response.success, 'success');
                } else if (response.hasOwnProperty("error")) {
                    console.error(response.error);
                    Swal.fire('Error', response.error, 'error');
                } else {
                    console.error("Respuesta del servidor inesperada");
                    Swal.fire('Error', "Se produjo un error inesperado al procesar la solicitud. Por favor, inténtelo nuevamente.", 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire('Error', "Se produjo un error al enviar la solicitud. Por favor, inténtelo nuevamente.", 'error');
            }
        });
    });
    
    $("#botonCrear").click(() => {
        $("#contrato-form").trigger("reset");
        $("#NoContrato").prop("disabled", false);
        edit = false;
    });

    // Mostrar datos en la tabla
    function fetchTasks() {
        $.ajax({
            url: "php/tabla-contratos.php",
            type: "GET",
            success: function(response) {
                const tasks = JSON.parse(response);
                let template = ``;
                tasks.forEach(task => {
                    template += `
                    <tr taskId="${task.NoContrato}">
                        <td>${task.NoContrato}</td>
                        <td>${task.NoFianza}</td>
                        <td>${task.NoProveedor}</td>
                        <td>${task.NomProveedor}</td>
                        <td>${task.MontoMin}</td>
                        <td>${task.MontoMax}</td>
                        <td>${task.VigenciaInicio}</td>
                        <td>${task.VigenciaFin}</td>
                        
                        ${userRole === 1 ? `<td><button class="btn btn-danger text-center task-delete"><i class="bi bi-trash"></i></button>
                                            <button class="btn btn-primary text-center task-item"><i class="bi bi-pencil-square"></i></button></tr>` : ''}
                    
                    `;
                });
                $("#tasks").html(template);
            }
        });
    }

    // ELIMINAR
    $(document).on("click", ".task-delete", function() {
        const element = $(this).closest('tr');
        const NoContrato = $(element).attr("taskId");

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
                $.post("php/eliminar-contrato.php", { NoContrato }, () => {
                    fetchTasks();
                    Swal.fire('¡Eliminado!', 'El contrato ha sido eliminado.', 'success');
                });
            }
        });
    });

    // Evento para hacer clic en un elemento para editar el contrato
    $(document).on("click", ".task-item", function() {
        const element = $(this).closest('tr');
        const NoContrato = $(element).attr("taskId");
       
        $("#NoContrato").prop("disabled", true); 
        $.ajax({
            url: "php/obtener-un-contrato.php",
            data: { NoContrato },
            type: "POST",
            success: function(response) {
                const contrato = JSON.parse(response);
                $("#NoContrato").val(contrato.NoContrato);
                $("#NoFianza").val(contrato.NoFianza);
                $("#NoProveedor").val(contrato.NoProveedor);
                $("#NomProveedor").val(contrato.NomProveedor);
                $("#MontoMin").val(contrato.MontoMin);
                $("#MontoMax").val(contrato.MontoMax);
                $("#VigenciaInicio").val(contrato.VigenciaInicio);
                $("#VigenciaFin").val(contrato.VigenciaFin);
                $("#taskId").val(contrato.NoContrato);
                $("#myModal").modal("show");
                edit = true;
            },
        });
    });

    // NUMERO DE PROVEEDOR SELECT
    $(document).ready(function() {
        $.ajax({
            url: "php/getNoProveedor.php",
            type: "GET",
            success: function(response) {
                var options = "";
                var proveedores = JSON.parse(response);
                proveedores.forEach(function(proveedor) {
                    options += "<option value='" + proveedor.NoProveedor + "'>" + proveedor.NoProveedor + "</option>";
                });
                $("#NoProveedor").append(options);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    
        $("#NoProveedor").change(function() {
            var NoProveedor = $(this).val();
            $.ajax({
                url: "php/getNomProveedor.php",
                type: "POST",
                data: { NoProveedor: NoProveedor },
                success: function(response) {
                    $("#NomProveedor").val(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
});
