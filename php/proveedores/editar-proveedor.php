<?php
include("../database.php");

if(isset($_POST["NoProveedor"], $_POST["NomProveedor"], $_POST["modo"])) {
    $modo = $_POST["modo"];
        mysqli_begin_transaction($connecction);
    if ($modo === "editar") {
        $NoProveedor = $_POST["NoProveedor"];
        $NomProveedor = $_POST["NomProveedor"];
        
        try {
            $updateQuery = "UPDATE proveedores 
                            SET NomProveedor=?
                            WHERE NoProveedor=?";
            $updateStmt = mysqli_prepare($connecction, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ss", $NomProveedor, $NoProveedor);
            mysqli_stmt_execute($updateStmt);
            
            if(mysqli_stmt_affected_rows($updateStmt) < 1) {
                throw new Exception("Error al actualizar el proveedor: No se encontró el proveedor");
            }

            mysqli_commit($connecction);

            echo json_encode(["success" => "Proveedor actualizado correctamente!"]);
        } catch(Exception $e) {
            mysqli_rollback($connecction);

            echo json_encode(["error" => $e->getMessage()]);
        }

        mysqli_stmt_close($updateStmt);
    } else {
        echo "Error: Acción no válida.";
    }
} else {
    echo "No se han recibido todos los datos del proveedor para actualizar.";
}

