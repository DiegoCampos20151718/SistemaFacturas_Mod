<?php
include("../database.php");

if(isset($_POST["NoProveedor"])) {
    $NoProveedor = $_POST["NoProveedor"];

    
    $query = "SELECT * FROM proveedores WHERE NoProveedor = ?";
              
    $stmt = mysqli_prepare($connecction, $query);
    mysqli_stmt_bind_param($stmt, "s", $NoProveedor);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result && mysqli_num_rows($result) > 0) {
        
        $proveedor = mysqli_fetch_assoc($result);
        echo json_encode($proveedor);
    } else {
        
        echo json_encode(["error" => "No se encontró el proveedor"]);
    }

    mysqli_stmt_close($stmt);
} else {
   
    echo json_encode(["error" => "No se proporcionó el identificador del proveedor"]);
}

