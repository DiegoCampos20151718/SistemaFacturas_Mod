<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "facturas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Asegúrate de que todas las variables están disponibles
$proveedor = $_POST['proveedor'] ?? null;
$tipo = $_POST['tipo'] ?? null;
$contrato = $_POST['contrato'] ?? null;
$noFactura = $_POST['no-factura'] ?? null;
$fechaRegistro = $_POST['fecha-registro'] ?? null;
$fechaFactura = $_POST['fecha-factura'] ?? null;
$concepto = $_POST['concepto'] ?? null;
$observaciones = $_POST['observaciones'] ?? null;
$copiaFactura = $_FILES['copia-factura']['name'] ?? null;
$oficina = $_POST['oficina'] ?? null; // Corregido

// Validaciones
if (empty($proveedor)) {
    echo "Error: Debe seleccionar un proveedor.";
    exit;
}

if (empty($noFactura)) {
    echo "Error: Debe ingresar un número de factura.";
    exit;
}

if ($tipo == 'contrato' && empty($contrato)) {
    echo "Error: Debe seleccionar un contrato.";
    exit;
} elseif ($tipo != 'contrato') {
    $codificacion = $_POST['codificacion'] ?? null;
    if (empty($codificacion)) {
        echo "Error: Debe seleccionar una codificación.";
        exit;
    }
}

$query = "INSERT INTO facturas (NoProveedor, NomProveedor, NoContrato, NoFactura, Fecha, FechaDeFactura, Concepto, Observaciones, Tipo, CopiaFactura, oficina) 
          VALUES ('$proveedor', (SELECT NomProveedor FROM proveedores WHERE NoProveedor = '$proveedor'), '$contrato', '$noFactura', '$fechaRegistro', '$fechaFactura', '$concepto', '$observaciones', '$tipo', '$copiaFactura', '$oficina')";

if ($conexion->query($query) === TRUE) {
    $ultimoId = $conexion->insert_id;

    // Guardar las cuentas primarias
    if (isset($_POST['codificacion-primaria'])) {
        $cuentaP = substr($_POST['codificacion-primaria'], 0, 8);
        $udeiP = substr($_POST['codificacion-primaria'], 8, 5);
        $ccP = substr($_POST['codificacion-primaria'], 13, 7);
        $montoP = $_POST['monto-primario'];

        // foreach ($cuentaP as $key => $value) {
            $query = "INSERT INTO cuentas (NoFactura, cuenta, udei, cc, monto, concepto, fecha, codificacion) 
                      VALUES ('$noFactura', '$cuentaP', '$udeiP', '$ccP', '$montoP', 'P', '$fechaFactura', CONCAT('$cuentaP', '$udeiP', '$ccP'))";
            $conexion->query($query);
        // }
    }

    // Guardar las cuentas de apoyo
    if (isset($_POST['codificacion'])) {
        $cuentas = substr($_POST['codificacion'], 0, 8);
        $udeis = substr($_POST['codificacion'], 8, 5);
        $ccs = substr($_POST['codificacion'], 13, 7);
        $montos = $_POST['monto'];

        foreach ($cuentas as $key => $value) {
            $query = "INSERT INTO cuentas (NoFactura, cuenta, udei, cc, monto, concepto, fecha, codificacion) 
                      VALUES ('$noFactura', '$cuentas', '$udeis', '$ccs', '$montos', 'A', '$fechaFactura', CONCAT('$cuentas', '$udeis', '$ccs'))";
            $conexion->query($query);
        }
    }

    // Manejar la copia de la factura
    if (isset($_FILES['copia-factura']) && $_FILES['copia-factura']['error'] == UPLOAD_ERR_OK) {
        $archivoSubido = $_FILES['copia-factura'];
        $tipoArchivo = $archivoSubido['type'];
        $nombreArchivoOriginal = $archivoSubido['name'];
        $nombreArchivoSinEspacios = str_replace(' ', '_', $nombreArchivoOriginal); // Reemplazar espacios en el nombre original
        $extension = pathinfo($nombreArchivoSinEspacios, PATHINFO_EXTENSION);
        $nombreSinEspacios = str_replace(' ', '_', $noFactura);
        $copiaFactura = $nombreSinEspacios . "." . $extension;
        $rutaDestino = './copias_facturas/' . $copiaFactura;

        if (move_uploaded_file($archivoSubido['tmp_name'], $rutaDestino)) {
            $query = "UPDATE facturas SET CopiaFactura = '$copiaFactura' WHERE NoFactura = '$noFactura'";
            if ($conexion->query($query)) {
                echo "Factura guardada correctamente.";
            } else {
                echo "Error al guardar la factura: " . $conexion->error;
            }
        } else {
            echo "Error al subir el archivo.";
        }
    } else {
        echo "No se proporcionó una copia de la factura.";
    }

} else {
    echo "Error: " . $query . "<br>" . $conexion->error;
}

$conexion->close();