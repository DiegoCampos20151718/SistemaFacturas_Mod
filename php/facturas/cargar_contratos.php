<?php

$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$proveedor = $_GET['proveedor'];

$query = "SELECT NoContrato FROM contratos WHERE NoProveedor = '$proveedor'";
$resultado = $conexion->query($query);

$contratos = array();

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $contratos[] = $fila;
    }
}

echo json_encode($contratos);


$conexion->close();