$(document).ready(function() {

    function fetchCodificaciones() {
        $.ajax({
            url: 'php/disponibleC/fetch_codificaciones.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#codificacion-filter').empty().append('<option value="">Selecciona una opción</option>');
                data.forEach(function(codificacion) {
                    $('#codificacion-filter').append(`
                        <option value="${codificacion.codificacion}">${codificacion.codificacion}</option>
                    `);
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

    function fetchYears(codificacion) {
        $.ajax({
            url: 'php/disponibleC/fetch_years.php',
            type: 'GET',
            data: { codificacion: codificacion },
            dataType: 'json',
            success: function(data) {
                $('#year-filter').empty().append('<option value="">Selecciona año</option>');
                data.forEach(function(year) {
                    $('#year-filter').append(`
                        <option value="${year}">${year}</option>
                    `);
                });
                $('#year-filter').prop('disabled', false); 
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

    function fetchFilteredData(codificacion, year) {
        if (!codificacion || !year) {
            $('#data-table').empty();  
            return;
        }
        
        $.ajax({
            url: 'php/disponibleC/get_contratos.php',
            type: 'GET',
            data: { codificacion: codificacion, year: year },
            dataType: 'json',
            success: function(data) {
                $('#data-table').empty();
                data.forEach(function(row) {
                    $('#data-table').append(`
                        <tr>
                            <td>${row.codificacion}</td>
                            <td>${row.periodo}</td>
                            <td>${row.importeDef}</td>
                            <td>${row.numCargos}</td>
                            <td>${row.importeCargos}</td>
                            <td>${row.disponibleAcumulado}</td>
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

    // Manejar el cambio de selección en el select de codificación
    $('#codificacion-filter').on('change', function() {
        const codificacion = $(this).val();
        $('#data-table').empty();  
        if (codificacion) {
            fetchYears(codificacion);
        } else {
            $('#year-filter').empty().append('<option value="">Selecciona año</option>')
        }
    });

    // Manejar el cambio de selección en el select de año
    $('#year-filter').on('change', function() {
        const codificacion = $('#codificacion-filter').val();
        const year = $(this).val();
        fetchFilteredData(codificacion, year);
    });

    fetchCodificaciones();
});
