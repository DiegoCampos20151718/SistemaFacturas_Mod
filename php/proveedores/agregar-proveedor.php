<?php
include("../database.php");

$response = array();

if(isset($_POST["NoProveedor"], $_POST["NomProveedor"], $_POST["modo"])) {
    $modo = $_POST["modo"];
    
    if ($modo === "agregar") {
        $task_NoProveedor = filter_input(INPUT_POST, "NoProveedor", FILTER_SANITIZE_STRING);
        $task_NomProveedor = filter_input(INPUT_POST, "NomProveedor", FILTER_SANITIZE_STRING);

        $query_check = "SELECT COUNT(*) AS count FROM proveedores WHERE NoProveedor = ?";
        $stmt_check = mysqli_prepare($connecction, $query_check);
        mysqli_stmt_bind_param($stmt_check, "s", $task_NoProveedor);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $count);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);

        if ($count > 0) {
            $response["error"] = "Error: NoProveedor duplicado. El número de contrato ya existe en la base de datos.";
        } else {
            $query = "INSERT INTO proveedores (NoProveedor, NomProveedor)
                  VALUES (?,?)";
            $stmt = mysqli_prepare($connecction, $query);

            mysqli_stmt_bind_param($stmt, "ss",$task_NoProveedor, $task_NomProveedor);

            if (mysqli_stmt_execute($stmt)) {
                $response["success"] = "Proveedor agregado!";
            } else {
                $response["error"] = "Error en la consulta: " . mysqli_error($connecction);
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        $response["error"] = "Error: Acción no válida.";
    }
} else {
    $response["error"] = "No se han recibido todos los parámetros necesarios.";
}

echo json_encode($response);

