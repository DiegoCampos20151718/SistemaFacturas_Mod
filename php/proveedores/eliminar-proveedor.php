<?php

include("../database.php");


if(isset($_POST["NoProveedor"])) {
   
    $NoProveedor = $_POST["NoProveedor"];

    
    $query = "DELETE FROM proveedores WHERE NoProveedor = ?";
    
  
    $stmt = $connecction->prepare($query);
    
  
    $stmt->bind_param("s", $NoProveedor);
    
  
    if($stmt->execute()) {
       
        echo "El proveedor ha sido eliminado exitosamente";
    } else {
       
        echo "Error al eliminar el proveedor: " . $connecction->error;
    }

   
    $stmt->close();
    $connecction->close();
} else {
    
    echo "No se proporcionó un proveedor para eliminar";
}

