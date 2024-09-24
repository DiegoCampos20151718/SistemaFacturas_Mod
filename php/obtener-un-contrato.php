<?php
include("database.php");

if(isset($_POST["NoContrato"])) {
    $NoContrato = $_POST["NoContrato"];

    $query = "SELECT c.*, p.NomProveedor 
              FROM contratos c 
              INNER JOIN proveedores p ON c.NoProveedor = p.NoProveedor
              WHERE c.NoContrato = ?";
              
    $stmt = mysqli_prepare($connecction, $query);
    mysqli_stmt_bind_param($stmt, "s", $NoContrato);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result && mysqli_num_rows($result) > 0) {
        $contrato = mysqli_fetch_assoc($result);
        echo json_encode($contrato);
    } else {
        echo json_encode(["error" => "No se encontró el contrato"]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["error" => "No se proporcionó el identificador del contrato"]);
}

