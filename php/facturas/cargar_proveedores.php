<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$query = "SELECT NoProveedor, NomProveedor FROM proveedores";
$resultado = $conexion->query($query);

$proveedores = array();


if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $proveedores[] = $fila;
    }
}


echo json_encode($proveedores);


$conexion->close();