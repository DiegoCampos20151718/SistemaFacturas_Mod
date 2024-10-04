<?php

include("../session.php");
session_start();  // Correctamente iniciar sesión

$matricula = $_POST['matricula'];  // Usar matricula en vez de email
$password = $_POST['password'];

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'facturas');
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Consulta preparada para evitar inyección SQL
$query = "SELECT id, matricula, nombre, apellido, rol, oficina, unidad, password FROM usuarios WHERE matricula = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $matricula);  // Cambiar email a matricula
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verificar la contraseña usando password_verify
    if (password_verify($password, $row['password'])) {
        // Iniciar sesión y almacenar los datos del usuario en la sesión
        $_SESSION['id'] = $row['id'];
        $_SESSION['matricula'] = $row['matricula'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['apellido'] = $row['apellido'];
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['oficina'] = $row['oficina'];
        $_SESSION['unidad'] = $row['unidad'];

        // Respuesta exitosa en JSON
        echo json_encode(['success' => true]);
    } else {
        // Contraseña incorrecta
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} else {
    // Usuario no encontrado
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
