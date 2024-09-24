<?php
$conn = new mysqli("localhost", "root", "", "facturas");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$codificacion = isset($_GET['codificacion']) ? $_GET['codificacion'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

$sql = "SELECT 
    dm.codificacion, 
    dm.anio, 
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
    dm.codificacion = ? AND dm.anio = ?
GROUP BY 
    dm.codificacion, periodo, dm.anio, dm.mes
ORDER BY 
    dm.anio, dm.mes";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $codificacion, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
