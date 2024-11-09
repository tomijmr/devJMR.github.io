<?php
include("dbconnect.php");

// Obtener los clientes
$sql_clients = "SELECT id, name_razon_social FROM clients";
$result_clients = $conn->query($sql_clients);

// Obtener los productos
$sql_products = "SELECT id_product, desc_product, cost_product, stock_prod FROM product";
$result_products = $conn->query($sql_products);

// Obtener las listas de precios
$sql_lists = "SELECT id_list, name_list, coef_list FROM lists";
$result_lists = $conn->query($sql_lists);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturador POS - SaMS Lite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: block;
            color: #555;
            font-weight: bold;
            text-align: left;
            width: 100%;
            margin-bottom: 5px;
        }

        select, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #800000;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #660000;
        }

        .product-row {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 10px;
        }

        #products {
            margin-bottom: 20px;
            width: 100%;
        }

        #add-product-btn {
            background-color: #800000;
            padding: 8px 12px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
        }

        #add-product-btn:hover {
            background-color: #660000;
        }

        .section-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            width: 100%;
            text-align: left;
        }
    </style>
</head>
<body>

<form action="guardar_venta.php" method="POST">
<center>
<h1>Facturador POS</h1>
</center>
    <!-- Selección del Cliente -->
    <label for="client">Cliente:</label>
    <select name="client_id" id="client" required>
        <option value="">Seleccione un cliente</option>
        <?php
        if ($result_clients->num_rows > 0) {
            while ($client = $result_clients->fetch_assoc()) {
                echo "<option value='" . $client['id'] . "'>" . $client['name_razon_social'] . "</option>";
            }
        }
        ?>
    </select>

    <!-- Selección de Producto y Cantidad -->
    <div class="section-title">Productos</div>
    <div id="products">
        <div class="product-row">
            <label>Producto:</label>
            <select name="product_id[]" required>
                <option value="">Seleccione un producto</option>
                <?php
                if ($result_products->num_rows > 0) {
                    while ($product = $result_products->fetch_assoc()) {
                        echo "<option value='" . $product['id_product'] . "' data-cost='" . $product['cost_product'] . "'>" . $product['desc_product'] . " - $" . $product['cost_product'] . "</option>";
                    }
                }
                ?>
            </select>
            <label>Cantidad:</label>
            <input type="number" name="quantity[]" min="1" required>
        </div>
    </div>
    <button type="button" id="add-product-btn" onclick="addProductRow()">Agregar otro producto</button>

    <!-- Selección de Lista de Precios -->
    <div class="section-title">Financiación</div>
    <label for="price_list">Lista de Precios:</label>
    <select name="list_id" id="price_list" required>
        <option value="">Seleccione una lista</option>
        <?php
        if ($result_lists->num_rows > 0) {
            while ($list = $result_lists->fetch_assoc()) {
                echo "<option value='" . $list['id_list'] . "' data-coef='" . $list['coef_list'] . "'>" . $list['name_list'] . "</option>";
            }
        }
        ?>
    </select>

    <br><br>
    <button type="submit">Guardar Venta</button>
</form>

<script>
    // Función para añadir otra fila de producto
    function addProductRow() {
        var productsDiv = document.getElementById('products');
        var productRow = document.createElement('div');
        productRow.className = 'product-row';
        productRow.innerHTML = `
            <label>Producto:</label>
            <select name="product_id[]" required>
                <option value="">Seleccione un producto</option>
                <?php
                if ($result_products->num_rows > 0) {
                    $result_products->data_seek(0); // Reiniciar puntero de resultados
                    while ($product = $result_products->fetch_assoc()) {
                        echo "<option value='" . $product['id_product'] . "' data-cost='" . $product['cost_product'] . "'>" . $product['desc_product'] . " - $" . $product['cost_product'] . "</option>";
                    }
                }
                ?>
            </select>
            <label>Cantidad:</label>
            <input type="number" name="quantity[]" min="1" required>
        `;
        productsDiv.appendChild(productRow);
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
