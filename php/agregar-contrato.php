<?php
include("database.php");

$response = array();

if(isset($_POST["NoContrato"], $_POST["NoFianza"], $_POST["NoProveedor"], $_POST["MontoMin"], $_POST["MontoMax"], $_POST["VigenciaInicio"], $_POST["VigenciaFin"], $_POST["modo"])) {
    $modo = $_POST["modo"];
    
    if ($modo === "agregar") {
        $task_NoContrato = filter_input(INPUT_POST, "NoContrato", FILTER_SANITIZE_STRING);
        $task_NoFianza = filter_input(INPUT_POST, "NoFianza", FILTER_SANITIZE_STRING);
        $task_NoProveedor = filter_input(INPUT_POST, "NoProveedor", FILTER_SANITIZE_STRING);
        $task_MontoMin = filter_input(INPUT_POST, "MontoMin", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $task_MontoMax = filter_input(INPUT_POST, "MontoMax", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $task_VigenciaInicio = filter_input(INPUT_POST, "VigenciaInicio", FILTER_SANITIZE_STRING);
        $task_VigenciaFin = filter_input(INPUT_POST, "VigenciaFin", FILTER_SANITIZE_STRING);

        if ($task_MontoMin > $task_MontoMax) {
            $response["error"] = "Error: El Monto Mínimo no puede ser mayor al Monto Máximo.";
            echo json_encode($response);
            exit;
        }

        if ($task_VigenciaInicio > $task_VigenciaFin) {
            $response["error"] = "Error: La Fecha de Inicio no puede ser mayor a la Fecha de Fin.";
            echo json_encode($response);
            exit;
        }

        $query_check = "SELECT COUNT(*) AS count FROM contratos WHERE NoContrato = ?";
        $stmt_check = mysqli_prepare($connecction, $query_check);
        mysqli_stmt_bind_param($stmt_check, "s", $task_NoContrato);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $count);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);

        if ($count > 0) {
            $response["error"] = "Error: NoContrato duplicado. El número de contrato ya existe en la base de datos.";
        } else {
            $query = "INSERT INTO contratos (NoContrato, NoFianza, NoProveedor, MontoMin, MontoMax, VigenciaInicio, VigenciaFin)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connecction, $query);

            mysqli_stmt_bind_param($stmt, "sssddss", $task_NoContrato, $task_NoFianza, $task_NoProveedor, $task_MontoMin, $task_MontoMax, $task_VigenciaInicio, $task_VigenciaFin);

            if (mysqli_stmt_execute($stmt)) {
                $response["success"] = "Contrato agregado!";
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
