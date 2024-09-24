<?php
include("database.php");

if(isset($_POST["NoProveedor"])){
    $NoProveedor = $_POST["NoProveedor"];

    $query = "SELECT NomProveedor FROM proveedores WHERE NoProveedor = ?";
    $stmt = mysqli_prepare($connecction, $query);

    mysqli_stmt_bind_param($stmt, "s", $NoProveedor);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$NomProveedor);

    if(mysqli_stmt_fetch($stmt)) {
        echo $NomProveedor;
    } else {
        echo "Proveedor no encontrado";
    }

    mysqli_stmt_close($stmt);
}

