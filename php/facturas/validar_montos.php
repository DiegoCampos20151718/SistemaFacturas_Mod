<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$codificacion = $_POST["codificacion"];
$fechaRegistro = $_POST["fecha_registro"];

if (!empty($codificacion) && !empty($fechaRegistro)) {
    
    $sql = "
        SELECT 
            dm.codificacion,
            CONCAT(SUBSTRING(dm.anio, 3, 2), 'M', LPAD(dm.mes, 2, '0')) AS periodo,
            MAX(dm.importeDef) AS importeDef,
            COUNT(c.id) AS numCargos,
            COALESCE(SUM(c.monto), 0) AS importeCargos,
            (
                SELECT 
                    COALESCE(SUM(dms.importeDef), 0) - 
                    COALESCE((
                        SELECT SUM(c2.monto)
                        FROM cuentas c2
                        WHERE c2.codificacion = dm.codificacion
                        AND (YEAR(c2.fecha) < dm.anio OR (YEAR(c2.fecha) = dm.anio AND MONTH(c2.fecha) <= dm.mes))
                    ), 0)
                FROM 
                    disponibilidad_mensual dms
                WHERE 
                    dms.codificacion = dm.codificacion 
                    AND (dms.anio < dm.anio OR (dms.anio = dm.anio AND dms.mes <= dm.mes))
            ) AS disponibleAcumulado
        FROM 
            disponibilidad_mensual dm
        LEFT JOIN 
            cuentas c ON dm.codificacion = c.codificacion AND dm.anio = YEAR(c.fecha) AND dm.mes = MONTH(c.fecha)
        WHERE 
            dm.codificacion = ?
            AND dm.mes = MONTH(?)
            AND dm.anio = YEAR(?)
        GROUP BY 
            dm.codificacion, dm.anio, dm.mes
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $codificacion, $fechaRegistro, $fechaRegistro);
    $stmt->execute();
    $stmt->bind_result($codificacion, $periodo, $importeDef, $numCargos, $importeCargos, $disponibleAcumulado);

    if ($stmt->fetch()) {
        $response = array(
            'success' => true,
            'disponibleAcumulado' => $disponibleAcumulado
        );
    } else {
        $response = array(
            'success' => false,
            'mensaje' => 'No se encontró el disponible para la codificación y fecha de registro proporcionados.'
        );
    }

    $stmt->close();
} else {
    $response = array(
        'success' => false,
        'mensaje' => 'Falta la codificación o la fecha de registro.'
    );
}

header('Content-Type: application/json');
echo json_encode($response);

$conexion->close();
