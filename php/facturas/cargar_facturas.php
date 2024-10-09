<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "facturas");

// Obtener los valores de la sesión
$rol = $_SESSION["rol"];
$oficina = $_SESSION["oficina"];

// Construir la consulta SQL básica
$query = "SELECT f.NoFactura, c.NoContrato, p.NomProveedor, f.Fecha, f.FechaDeFactura, f.Concepto, f.Observaciones, f.Tipo, f.CopiaFactura, o.oficina,
          SUM(cu.monto) AS monto_total
          FROM facturas f
          INNER JOIN contratos c ON f.NoContrato = c.NoContrato
          INNER JOIN proveedores p ON c.NoProveedor = p.NoProveedor
          INNER JOIN oficinas o ON f.oficina = o.id
          LEFT JOIN cuentas cu ON f.NoFactura = cu.NoFactura";

// Si el rol no es 1, agregar condición para filtrar por oficina
if ($rol != 1) {
    $query .= " WHERE o.id = ?";
}

// Continuar con el agrupamiento
$query .= " GROUP BY f.NoFactura, c.NoContrato, p.NomProveedor, f.Fecha, f.FechaDeFactura, f.Concepto, f.Observaciones, f.Tipo, f.CopiaFactura";

// Preparar la consulta
$stmt = $conexion->prepare($query);

// Si el rol no es 1, enlazar la oficina como parámetro
if ($rol != 1) {
    $stmt->bind_param("i", $oficina);
}

// Ejecutar la consulta
$stmt->execute();
$resultado = $stmt->get_result();

// Mostrar la tabla con los resultados
echo '<table id="Data_Table" class="table table-striped table-bordered table-responsive table-hover text-center">';
echo '<thead><tr><th>No. de Factura</th><th>No. Contrato</th><th>Proveedor</th><th>Fecha de Registro</th><th>Fecha de Factura</th><th>Concepto</th><th>Observaciones</th><th>Tipo</th><th>Oficina</th><th>Copia de Factura</th><th>Monto Total</th><th>Acciones</th></tr></thead>';
echo '<tbody>';

while ($fila = $resultado->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $fila['NoFactura'] . '</td>';
    echo '<td>' . $fila['NoContrato'] . '</td>';
    echo '<td>' . $fila['NomProveedor'] . '</td>';
    echo '<td>' . $fila['Fecha'] . '</td>';
    echo '<td>' . $fila['FechaDeFactura'] . '</td>';
    echo '<td>' . $fila['Concepto'] . '</td>';
    echo '<td>' . $fila['Observaciones'] . '</td>';
    echo '<td>' . $fila['Tipo'] . '</td>';
    echo '<td>' . $fila['oficina'] . '</td>';
    echo '<td>' . $fila['CopiaFactura'] . '</td>';
    echo '<td>' . number_format($fila['monto_total'], 2) . '<button class="btn btn-info btn-detalle-monto" data-bs-toggle="modal" data-bs-target="#detalleModal" data-factura="' . $fila['NoFactura'] . '"><i class="bi bi-info-circle-fill"></i></button></td>';
    
    echo '<td>';
    echo '<button class="btn btn-primary btn-ver-factura" data-toggle="modal" data-target="#facturaModal" data-factura="' . $fila['NoFactura'] . '"><i class="bi bi-eye-fill"></i></button>';
    
    // Verificar si el rol es 1 para mostrar el botón de eliminar
    if ($rol == 1) {
        echo '<button class="btn btn-danger btn-eliminar-factura" data-factura="' . $fila['NoFactura'] . '"><i class="bi bi-trash-fill"></i></button>';
    }
    
    echo '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Cerrar la conexión
$conexion->close();
?>

