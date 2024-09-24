<?php

include("../database.php");
$query = "SELECT * FROM proveedores";
$result = mysqli_query($connecction, $query);

if(!$result){
    die("Hubo un error en la consulta". mysqli_error($connecction));
}

$json = array();

while($row = mysqli_fetch_array($result)){
    $json[] = array(
        "NoProveedor" => $row["NoProveedor"],
        "NomProveedor" => $row["NomProveedor"],
    );
    
}
$jsonstring = json_encode($json);
echo $jsonstring;
