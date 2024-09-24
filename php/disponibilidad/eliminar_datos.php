<?php
header('Content-Type: application/json');

if (isset($_POST['codificacion']) && isset($_POST['anio'])) {
    $codificacion = $_POST['codificacion'];
    $anio = $_POST['anio'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "facturas";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(array("success" => false, "message" => "Error de conexión: " . $conn->connect_error)));
    }

    $stmt = $conn->prepare("DELETE FROM disponibilidad_mensual WHERE codificacion = ? AND anio = ?");
    $stmt->bind_param("si", $codificacion, $anio);

    if ($stmt->execute()) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Error al eliminar los registros: " . $stmt->error));
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("success" => false, "message" => "Datos incompletos"));
}

