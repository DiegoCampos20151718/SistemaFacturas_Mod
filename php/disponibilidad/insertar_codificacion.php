<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$cuenta = $_POST["cuenta"];
$udei = $_POST["udei"];
$cc = $_POST["cc"];
$anio = $_POST["anio"];

// Validar los datos
if (empty($cuenta) || empty($udei) || empty($cc) || empty($anio)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
    exit;
}

// Construir la codificación
$codificacion = $cuenta . $udei . $cc;

// Preparar la consulta SQL
$sql = "INSERT INTO disponibilidad_mensual (codificacion, mes, anio) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);

// Insertar las 12 codificaciones para el año seleccionado
$insertados = 0;
for ($i = 1; $i <= 12; $i++) {
    $mes = sprintf("%02d", $i); 
    $stmt->bind_param("ssi", $codificacion, $mes, $anio);
    if ($stmt->execute()) {
        $insertados++;
    }
}

if ($insertados === 12) {
    echo json_encode(["success" => true, "message" => "Codificación agregada correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al agregar la codificación"]);
}

$stmt->close();
$conexion->close();

