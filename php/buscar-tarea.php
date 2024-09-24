<?php

include("database.php");

$search = $_POST["search"];

if(!empty($search)){
    $query = "SELECT contratos.*, proveedores.NomProveedor
            FROM contratos
            INNER JOIN proveedores ON contratos.NoProveedor = proveedores.NoProveedor 
            WHERE NoContrato LIKE '$search' ";
    $result = mysqli_query($connecction, $query);

    if(!$result){
        die("Error en la consulta". mysqli_error($connecction));
    }

    $json = array();

    while($row = mysqli_fetch_array($result)){
        $json[] = array(
            "NoContrato" =>$row["NoContrato"],
            "NoFianza" => $row["NoFianza"],
            "NoProveedor" => $row["NoProveedor"],
            "NomProveedor" => $row["NomProveedor"],
            "MontoMin" => $row["MontoMin"],
            "MontoMax" => $row["MontoMax"],
            "VigenciaInicio" => $row["VigenciaInicio"],
            "VigenciaFin" => $row["VigenciaFin"]
        );
    }
    //TRANSFORMACION DE ARRAY A OBJETO PARA PASARLO A LA FRONT
    $jsonstring = json_encode($json);
    echo $jsonstring;
}