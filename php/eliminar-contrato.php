<?php
include("database.php");

if(isset($_POST["NoContrato"])) {
    $NoContrato = $_POST["NoContrato"];

    $query = "DELETE FROM contratos WHERE NoContrato = ?";
    
    $stmt = $connecction->prepare($query);
    
    $stmt->bind_param("s", $NoContrato);
    
    if($stmt->execute()) {
        echo "El contrato ha sido eliminado exitosamente";
    } else {
        echo "Error al eliminar el contrato: " . $connecction->error;
    }
    $stmt->close();
    $connecction->close();
} else {
    echo "No se proporcionó un NoContrato para eliminar";
}

