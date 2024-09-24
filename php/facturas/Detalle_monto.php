<?php
$conexion = new mysqli("localhost", "root", "", "facturas");

if (isset($_POST['factura'])) {
    $factura = $_POST['factura'];
    
    $query = "SELECT cu.codificacion, cu.monto 
              FROM cuentas cu 
              WHERE cu.NoFactura = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $factura);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>Codificación</th><th>Monto</th></tr></thead>';
    echo '<tbody>';
    while ($fila = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $fila['codificacion'] . '</td>';
        echo '<td>' . number_format($fila['monto'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
    $stmt->close();
}

$conexion->close();

