<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "facturas";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $matricula = $_POST["matricula"];
    $password = $_POST["password"];
    $rol = $_POST["rol"];
    $oficina = $_POST["oficina"];

    // Verificar si el correo electrónico ya existe
    $stmt = $conn->prepare("SELECT matricula FROM usuarios WHERE matricula = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "error_matricula_exists";
        exit;
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, matricula, password, rol, oficina) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $matricula, $hashedPassword, $rol, $oficina);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
