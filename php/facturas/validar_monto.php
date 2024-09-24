<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturas");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$codificacion = $_POST['codificacion'];
$monto = (float)$_POST['monto'];
$fecha = $_POST['fecha'];

// Obtener el año y el mes de la fecha
$anio = date('Y', strtotime($fecha));
$mes = date('m', strtotime($fecha));

// Consulta para obtener el importe definido y los cargos de la codificación en el mes y año especificados
$query = "SELECT importeDef, cargos FROM disponibilidad_mensual WHERE codificacion = ? AND anio = ? AND mes = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("sss", $codificacion, $anio, $mes);
$stmt->execute();
$result = $stmt->get_result();

$response = array(
    "success" => false,
    "message" => "No se encontró la codificación para el mes y año especificados.",
    "disponible" => 0
);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $importeDef = (float)$row['importeDef'];
    $cargos = (float)$row['cargos'];

    // Consulta para obtener los cargos de la tabla "cuentas" para la misma codificación, mes y año
    $query2 = "SELECT SUM(monto) AS cargos_cuentas FROM cuentas WHERE codificacion = ? AND MONTH(fecha) = ? AND YEAR(fecha) = ?";
    $stmt2 = $conexion->prepare($query2);
    $stmt2->bind_param("ssi", $codificacion, $mes, $anio);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $cargos_cuentas = (float)$row2['cargos_cuentas'];
    } else {
        $cargos_cuentas = 0;
    }

    $disponible = $importeDef - $cargos - $cargos_cuentas;

    if ($monto <= $disponible) {
        $response['success'] = true;
        $response['message'] = "Monto válido.";
        $response['disponible'] = $disponible;
    } else {
        $response['message'] = "El monto supera el disponible.";
        $response['disponible'] = $disponible;
    }
}

echo json_encode($response);

$stmt->close();
$stmt2->close();
$conexion->close();
