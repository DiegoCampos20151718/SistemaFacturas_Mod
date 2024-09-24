<?php
ob_start();

$conn = new mysqli("localhost", "root", "", "facturas");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$codificacion = isset($_GET['codificacion']) ? $_GET['codificacion'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

$sql = "SELECT 
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
cuentas c ON dm.codificacion = c.codificacion AND dm.anio = YEAR(c.fecha) AND dm.mes = MONTH(c.fecha)";

$conditions = [];
$params = [];
$types = '';

if ($codificacion) {
    $conditions[] = "dm.codificacion = ?";
    $params[] = $codificacion;
    $types .= 's';
}

if ($year) {
    $conditions[] = "dm.anio = ?";
    $params[] = $year;
    $types .= 'i';
}

if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= " GROUP BY dm.codificacion, periodo, dm.anio, dm.mes
ORDER BY dm.codificacion, periodo";

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

// Obtener el contenido del búfer y limpiarlo
$output = ob_get_clean();
if (!empty($output)) {
    // Manejar el contenido adicional según sea necesario
    file_put_contents('error.log', $output, FILE_APPEND);
}

header('Content-Type: application/json');
echo json_encode($data);

