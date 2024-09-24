<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$codificacion = $_GET["codificacion"];

if (!empty($codificacion)) {
    $sql = "SELECT mes, anio, codificacion, importeDef, cargos FROM disponibilidad_mensual WHERE codificacion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $codificacion);
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
} else {
    echo json_encode([]);
}

$conexion->close();