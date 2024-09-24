$(document).ready(function() {
    // Validación del formulario de registro
    $('#register-form').submit(function(event) {
        event.preventDefault();

        // Obtener los valores de los campos
        var firstName = $('#exampleFirstName').val();
        var lastName = $('#exampleLastName').val();
        var email = $('#exampleInputEmail').val();
        var password = $('#exampleInputPassword').val();
        var confirmPassword = $('#exampleRepeatPassword').val();

        // Validar campos
        var isValid = true;
        if (firstName.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa tu nombre.', 'error');
        } else if (lastName.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa tu apellido.', 'error');
        } else if (email.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa tu correo electrónico.', 'error');
        } else if (password.trim() === '') {
            isValid = false;
            Swal.fire('Error', 'Por favor, ingresa una contraseña.', 'error');
        } else if (password !== confirmPassword) {
            isValid = false;
            Swal.fire('Error', 'Las contraseñas no coinciden.', 'error');
        }

        if (isValid) {
            // Enviar el formulario al servidor usando AJAX
            $.ajax({
                type: 'POST',
                url: 'php/Login/register.php',
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    password: password
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Registro exitoso',
                        text: 'Redireccionando a la página de login...',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'login.php';
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
        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            type: 'POST',
            url: 'php/Login/login.php',
            data: { email: email, password: password },
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
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al iniciar sesión. Por favor, inténtalo de nuevo.', 'error');
            }
        });
    });
});
