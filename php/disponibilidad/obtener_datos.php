<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$codificacion = isset($_GET["codificacion"]) ? $_GET["codificacion"] : '';
$year = isset($_GET["year"]) ? $_GET["year"] : '';

if (!empty($codificacion) && !empty($year)) {
    // Consulta con filtrado por codificación y año
    $sql = "SELECT mes, anio, codificacion, importeDef, cargos FROM disponibilidad_mensual WHERE codificacion = ? AND anio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $codificacion, $year);
} elseif (!empty($codificacion)) {
    // Consulta con filtrado solo por codificación
    $sql = "SELECT mes, anio, codificacion, importeDef, cargos FROM disponibilidad_mensual WHERE codificacion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $codificacion);
} else {
    echo json_encode([]);
    $conexion->close();
    exit();
}

$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $datos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
} else {
    echo json_encode([]);
}

$stmt->close();
$conexion->close();
?>
