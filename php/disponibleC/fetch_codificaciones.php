<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$query = "SELECT DISTINCT codificacion FROM disponibilidad_mensual";
$resultado = $conexion->query($query);

$codificaciones = array();

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $codificaciones[] = $fila;
    }
}

echo json_encode($codificaciones);

$conexion->close();