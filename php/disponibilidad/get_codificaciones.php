<?php
$conn = new mysqli("localhost", "root", "", "facturas");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT codificacion FROM disponibilidad_mensual ORDER BY codificacion";
$result = $conn->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row['codificacion'];
    }
}

echo json_encode($data);
$conn->close();