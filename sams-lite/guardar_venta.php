<?php
// Conectar a la base de datos
include("dbconnect.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del cliente
    $clienteId = $_POST['client_id'];
    $listaId = $_POST['list_id'];
    
    // Obtener datos del cliente y de la lista de precios
    $clienteQuery = "SELECT name_razon_social FROM clients WHERE id = ?";
    $stmtCliente = $conn->prepare($clienteQuery);
    $stmtCliente->bind_param("i", $clienteId);
    $stmtCliente->execute();
    $clienteResult = $stmtCliente->get_result();
    $cliente = $clienteResult->fetch_assoc();
    $nombreCliente = $cliente['name_razon_social'];

    $listaQuery = "SELECT name_list, coef_list FROM lists WHERE id_list = ?";
    $stmtLista = $conn->prepare($listaQuery);
    $stmtLista->bind_param("i", $listaId);
    $stmtLista->execute();
    $listaResult = $stmtLista->get_result();
    $lista = $listaResult->fetch_assoc();
    $nombreLista = $lista['name_list'];
    $coefLista = $lista['coef_list'];

    // Iterar sobre los productos seleccionados y guardar cada uno en la tabla ventas
    foreach ($_POST['product_id'] as $index => $productId) {
        $cantidad = $_POST['quantity'][$index];

        // Obtener detalles del producto
        $productoQuery = "SELECT desc_product, cost_product FROM product WHERE id_product = ?";
        $stmtProducto = $conn->prepare($productoQuery);
        $stmtProducto->bind_param("i", $productId);
        $stmtProducto->execute();
        $productoResult = $stmtProducto->get_result();
        $producto = $productoResult->fetch_assoc();
        
        $nombreProducto = $producto['desc_product'];
        $precioCosto = $producto['cost_product'];
        $precioVenta = $precioCosto * $coefLista;

        // Insertar los datos de la venta en la tabla ventas
        $insertVentaQuery = "INSERT INTO ventas (id_cliente, nombre_cliente, id_producto, nombre_producto, cantidad, precio_costo, precio_venta, lista_precio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertVenta = $conn->prepare($insertVentaQuery);
        $stmtInsertVenta->bind_param("isisiids", $clienteId, $nombreCliente, $productId, $nombreProducto, $cantidad, $precioCosto, $precioVenta, $nombreLista);

        if ($stmtInsertVenta->execute()) {
            echo "Producto '$nombreProducto' agregado a la venta con éxito.<br>";
        } else {
            echo "Error al guardar el producto '$nombreProducto' en la venta: " . $conn->error . "<br>";
        }
    }

    echo "<br>Venta registrada exitosamente.";
} else {
    echo "No se recibieron datos para guardar la venta.";
}

// Cerrar conexión
$conn->close();
?>
