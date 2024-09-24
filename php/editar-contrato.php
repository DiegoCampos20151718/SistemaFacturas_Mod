<?php
include("database.php");

if(isset($_POST["NoContrato"], $_POST["NoFianza"], $_POST["NoProveedor"], $_POST["MontoMin"], $_POST["MontoMax"], $_POST["VigenciaInicio"], $_POST["VigenciaFin"], $_POST["modo"])) {
    $modo = $_POST["modo"];
    
    if ($modo === "editar") {
        $NoContrato = $_POST["NoContrato"];
        $NoFianza = $_POST["NoFianza"];
        $NoProveedor = $_POST["NoProveedor"];
        $MontoMin = $_POST["MontoMin"];
        $MontoMax = $_POST["MontoMax"];
        $VigenciaInicio = $_POST["VigenciaInicio"];
        $VigenciaFin = $_POST["VigenciaFin"];
        
        $updateQuery = "UPDATE contratos 
                        SET NoFianza=?, NoProveedor=?, MontoMin=?, MontoMax=?, VigenciaInicio=?, VigenciaFin=? 
                        WHERE NoContrato=?";
        $updateStmt = mysqli_prepare($connecction, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "ssddsss", $NoFianza, $NoProveedor, $MontoMin, $MontoMax, $VigenciaInicio, $VigenciaFin, $NoContrato);

        if(mysqli_stmt_execute($updateStmt)) {
            echo json_encode(["success" => "Contrato actualizado correctamente!"]);
        } else {
            echo json_encode(["error" => "Error al actualizar el contrato: " . mysqli_error($connecction)]);
        }

        mysqli_stmt_close($updateStmt);
    } else {
        echo "Error: Acción no válida.";
    }
} else {
    echo "No se han recibido todos los datos del contrato para actualizar.";
}

