
$(document).ready(function() {
    $.ajax({
        type: 'GET',
        url: 'php/session.php',
        dataType: 'json',
        success: function(data) {
            if (data.isLoggedIn) {
                $('#usuario').text(data.usuario);
                $('#nombre').text(data.firstName);
                $('#apellido').text(data.lastName);
                $('#role').text(data.role);
            } else {
                window.location.href = 'index.html';
            }
        }
    });

    $('#logoutBtn').click(function() {
        $.ajax({
            type: 'GET',
            url: 'php/logout.php',
            success: function() {
                window.location.href = 'login.html';
            }
        });
    });
});