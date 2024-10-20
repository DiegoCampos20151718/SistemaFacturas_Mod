<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos
$connecction = new mysqli("localhost","root","","facturas");
if ($connecction->connect_error) {
    die("Connection failed: " . $connecction->connect_error);
}

// Obtener el año de la solicitud
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Consultas a la base de datos
// Facturas pagadas por mes (evolución)
$stmt_facturas_mes = $connecction->prepare("SELECT MONTH(FechaDeFactura) AS mes, COUNT(*) AS total_facturas FROM facturas WHERE YEAR(FechaDeFactura) = ? GROUP BY MONTH(FechaDeFactura)");
$stmt_facturas_mes->bind_param("i", $year);
$stmt_facturas_mes->execute();
$result_facturas_mes = $stmt_facturas_mes->get_result();
$facturas_por_mes = [];
while ($row = $result_facturas_mes->fetch_assoc()) {
    $facturas_por_mes[$row['mes']] = $row['total_facturas'];
}
$stmt_facturas_mes->close();

// Total de facturas pagadas por contrato
$stmt_facturas_contrato = $connecction->prepare("SELECT facturas.NoContrato AS contrato, COUNT(facturas.NoFactura) AS total_facturas FROM facturas JOIN contratos ON facturas.NoContrato = contratos.NoContrato GROUP BY facturas.NoContrato");
$stmt_facturas_contrato->execute();
$result_facturas_contrato = $stmt_facturas_contrato->get_result();
$facturas_por_contrato = [];
while ($row = $result_facturas_contrato->fetch_assoc()) {
    $facturas_por_contrato[] = ['contrato' => $row['contrato'], 'total' => $row['total_facturas']];
}
$stmt_facturas_contrato->close();

// Gastos vs. monto máximo del contrato
// Gastos vs. monto máximo del contrato
$stmt_gastos_contrato = $connecction->prepare("
    SELECT 
        contratos.NoContrato AS nombre, 
        contratos.MontoMax AS monto_maximo, 
        SUM(cuentas.monto) AS total_gastado 
    FROM cuentas 
    LEFT JOIN facturas ON facturas.NoFactura = cuentas.NoFactura
    LEFT JOIN contratos ON contratos.NoContrato = facturas.NoContrato
    GROUP BY contratos.NoContrato
");

$stmt_gastos_contrato->execute();
$result_gastos_contrato = $stmt_gastos_contrato->get_result();
$gastos_contrato = [];
while ($row = $result_gastos_contrato->fetch_assoc()) {
    $gastos_contrato[] = [
        'nombre' => $row['nombre'], 
        'monto_maximo' => $row['monto_maximo'], 
        'total_gastado' => $row['total_gastado'] ? $row['total_gastado'] : 0
    ];
}


// Devolver los datos en formato JSON
$response = [
    'facturas_por_mes' => array_values($facturas_por_mes),
    'facturas_por_contrato' => $facturas_por_contrato,
    'gastos_contrato' => $gastos_contrato
];
header('Content-Type: application/json');
echo json_encode($response); // Esto ya es suficiente para devolver el JSON correcto
exit;
