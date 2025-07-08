<?php
require 'conexion.php';

header('Content-Type: application/json');

$id_product = isset($_GET['id_product']) ? intval($_GET['id_product']) : 0;

$queryProduct = "SELECT cost_product FROM product WHERE id_product = $id_product";
$resultProduct = $conn->query($queryProduct);
$product = $resultProduct->fetch_assoc();

if (!$product) {
    echo json_encode([]);
    exit;
}

$cost = $product['cost_product'];

$queryLists = "SELECT name_list, coef_list FROM lists ORDER BY name_list";
$resultLists = $conn->query($queryLists);

$data = [];

while ($list = $resultLists->fetch_assoc()) {
    $precio_final = $cost * $list['coef_list'];
    $data[] = [
        'lista' => $list['name_list'],
        'coef' => $list['coef_list'],
        'precio' => number_format($precio_final, 2, ',', '.')
    ];
}

echo json_encode($data);
$conn->close();
