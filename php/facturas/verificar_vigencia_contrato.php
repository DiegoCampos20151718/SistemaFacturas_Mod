<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$contratoId = $_POST['contrato'];
$fechaRegistro = $_POST['fecha_registro'];

$query = "SELECT * FROM contratos WHERE NoContrato = '$contratoId' AND '$fechaRegistro' BETWEEN VigenciaInicio AND VigenciaFin";
$resultado = $conexion->query($query);

$response = array('valid' => false);

if ($resultado->num_rows > 0) {
    $response['valid'] = true;
}

echo json_encode($response);

$conexion->close();
