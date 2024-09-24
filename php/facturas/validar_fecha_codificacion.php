<?php

$conexion = new mysqli("localhost", "root", "", "facturas");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$codificacion = $_POST['codificacion'];
$fecha = $_POST['fecha'];


$anio = date('Y', strtotime($fecha));
$mes = date('m', strtotime($fecha));


$query = "SELECT importeDef FROM disponibilidad_mensual WHERE codificacion = ? AND anio = ? AND mes = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("sss", $codificacion, $anio, $mes);
$stmt->execute();
$result = $stmt->get_result();

$response = array(
    "success" => false,
    "message" => "No se encontró la codificación para el mes y año especificados.",
    "importeDef" => 0
);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['success'] = true;
    $response['message'] = "Codificación encontrada.";
    $response['importeDef'] = $row['importeDef'];
}

echo json_encode($response);

$stmt->close();
$conexion->close();
