<?php
$conn = new mysqli("localhost", "root", "", "facturas");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$codificacion = isset($_GET['codificacion']) ? $_GET['codificacion'] : '';

$sql = "SELECT DISTINCT anio 
        FROM disponibilidad_mensual 
        WHERE codificacion = ?
        ORDER BY anio";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $codificacion);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row['anio'];
    }
}

echo json_encode($data);
$conn->close();
?>
