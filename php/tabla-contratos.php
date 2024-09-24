<?php

include("database.php");

$query = "SELECT contratos.*, proveedores.NomProveedor
          FROM contratos
          INNER JOIN proveedores ON contratos.NoProveedor = proveedores.NoProveedor";
$result = mysqli_query($connecction, $query);

if(!$result){
    die("Hubo un error en la consulta". mysqli_error($connecction));
}

$json = array();

while($row = mysqli_fetch_array($result)){
    $json[] = array(
        "NoContrato" =>$row["NoContrato"],
        "NoFianza" =>$row["NoFianza"],
        "NoProveedor" => $row["NoProveedor"],
        "NomProveedor" => $row["NomProveedor"],
        "MontoMin" => $row["MontoMin"],
        "MontoMax" => $row["MontoMax"],
        "VigenciaInicio" => $row["VigenciaInicio"],
        "VigenciaFin" => $row["VigenciaFin"]
    );
    
}
$jsonstring = json_encode($json);
echo $jsonstring;
