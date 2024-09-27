<?php

include("../session.php");

$email = $_POST['matricula'];
$password = $_POST['password'];

$conn = new mysqli('localhost', 'root', '', 'facturas');
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$query = "SELECT id, matricula, nombre, apellido, rol, password FROM usuarios WHERE matricula = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $matricula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        startSession();
        $_SESSION['id'] = $row['id'];
        $_SESSION['matricula'] = $row['matricula'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['apellido'] = $row['apellido'];
        $_SESSION['rol'] = $row['rol'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
}

$stmt->close();
$conn->close();