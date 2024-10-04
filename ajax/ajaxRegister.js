$(document).ready(function() {
    // Validación del formulario de registro
    $('#register-form').submit(function(event) {
        event.preventDefault();

        // Obtener los valores de los campos
        var firstName = $('#exampleFirstName').val();
        var lastName = $('#exampleLastName').val();
        var matricula = $('#exampleMatricula').val();
        var password = $('#exampleInputPassword').val();
        var confirmPassword = $('#exampleRepeatPassword').val();
        var rol = $('#exampleRol').val();
        var oficina = $('#exampleOficina').val();

        // Validar campos
        var isValid = true;
        if (firstName.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa tu nombre.', 'error');
        } else if (lastName.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa tu apellido.', 'error');
        } else if (matricula.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa tu matricula.', 'error');
        } else if (password.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa una contraseña.', 'error');
        } else if (password !== confirmPassword) {
            isValid = false;
            Swal.fire('Error', 'Las contraseñas no coinciden.', 'error');
        }else if (rol.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Seleccione un rol valido', 'error');
        }else if (oficina.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Seleccione una oficina valida.', 'error');
        }

        if (isValid) {
            // Enviar el formulario al servidor usando AJAX
            $.ajax({
                type: 'POST',
                url: 'php/Login/register.php',
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    matricula: matricula,
                    password: password,
                    rol: rol,
                    oficina:oficina
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Registro exitoso',
                        text: 'Redireccionando a la página de login...',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'registerUser.php';
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al registrar el usuario. Por favor, inténtalo de nuevo.', 'error');
                }
            });
        }
    });

    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        // Obtener valores
        var matricula = $('#matricula').val().trim();
        var password = $('#password').val().trim();
    
        if (matricula === '' || password === '') {
            Swal.fire('Error', 'Por favor, ingresa tu matrícula y contraseña.', 'error');
            return;
        }
    
        // Enviar datos con AJAX
        $.ajax({
            type: 'POST',
            url: 'php/Login/login.php',
            data: { matricula: matricula, password: password },  // Verifica que envías 'matricula' y 'password'
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Inicio de sesión exitoso',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Ocurrió un error al iniciar sesión. Por favor, inténtalo de nuevo.';
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });    
});
