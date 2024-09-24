<?php
include("database.php");

$query = "SELECT DISTINCT NoProveedor FROM proveedores"; 
$result = mysqli_query($connecction, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

