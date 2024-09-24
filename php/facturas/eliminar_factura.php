<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$noFactura = $_POST['noFactura'];

// Eliminar registros de la tabla disponibilidad_mensual
$query = "DELETE FROM disponibilidad_mensual 
          WHERE cuentas_id IN (
              SELECT id 
              FROM cuentas 
              WHERE NoFactura = '$noFactura'
          )";

if ($conexion->query($query) === TRUE) {
    // Eliminar registros de la tabla cuentas
    $query = "DELETE FROM cuentas WHERE NoFactura = '$noFactura'";
    if ($conexion->query($query) === TRUE) {
        // Eliminar registro de la tabla facturas
        $query = "DELETE FROM facturas WHERE NoFactura = '$noFactura'";
        if ($conexion->query($query) === TRUE) {
            echo "Factura eliminada correctamente.";
        } else {
            echo "Error al eliminar la factura: " . $conexion->error;
        }
    } else {
        echo "Error al eliminar los registros relacionados: " . $conexion->error;
    }
} else {
    echo "Error al eliminar los registros relacionados: " . $conexion->error;
}

$conexion->close();
