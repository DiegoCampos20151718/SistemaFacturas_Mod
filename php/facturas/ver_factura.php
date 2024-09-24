<?php
$conexion = new mysqli("localhost", "root", "", "facturas");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_GET['factura'])) {
    $noFactura = $_GET['factura'];
    $query = "SELECT CopiaFactura FROM facturas WHERE NoFactura = '$noFactura'";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $nombreArchivo = $fila['CopiaFactura'];
        $rutaArchivo = './copias_facturas/' . $nombreArchivo;

        if (file_exists($rutaArchivo)) {
            $tipoArchivo = mime_content_type($rutaArchivo);
            header('Content-Type: ' . $tipoArchivo);
            header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($rutaArchivo));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            readfile($rutaArchivo);
            exit;
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "No se encontró la factura.";
    }
} else {
    echo "No se proporcionó un número de factura.";
}

$conexion->close();
