<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturas");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$mes = $_POST["mes"];
$anio = $_POST["anio"];
$codificacion = $_POST["codificacion"];
$importeDef = $_POST["importeDef"];
$cargos = $_POST["cargos"];

// Actualizar los datos en la base de datos
$sql = "UPDATE disponibilidad_mensual SET importeDef = ?, cargos = ? WHERE mes = ? AND anio = ? AND codificacion = ?";
$stmt = $conexion->prepare($sql);

// Verificar si los datos son números decimales y ajustar el tipo de dato de `bind_param`
$stmt->bind_param("ddiss", $importeDef, $cargos, $mes, $anio, $codificacion);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Datos actualizados correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar los datos: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
