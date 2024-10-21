<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";  // Cambia por tu usuario de la base de datos
$password = "";  // Cambia por tu contraseña
$dbname = "sams-cotizador";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener las listas de precios
$sql_lists = "SELECT id_list, name_list, coef_list FROM lists";
$result_lists = $conn->query($sql_lists);

// Crear un array para almacenar las listas
$lists = [];
if ($result_lists->num_rows > 0) {
    while ($row = $result_lists->fetch_assoc()) {
        $lists[] = $row;
    }
}

// Consulta para obtener los productos
$sql_products = "SELECT id_product, desc_product, cost_product FROM product";
$result_products = $conn->query($sql_products);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizador - SaMS Lite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #800000; /* Bordó estilo poncho salteño */
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #800000;
            color: white;
        }
        td {
            background-color: #ffd700; /* Color dorado */
        }
        a {
            display: inline-block;
            background-color: #ffffff; /* Dorado claro */
            color: #800000; /* Bordó para el texto */
            text-decoration: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        a:hover {
            background-color: #ffff11; /* Cambia el color de fondo al pasar el cursor */
            color: #ffd700; /* Cambia el texto a dorado */
        }
    </style>
</head>
<body>

    <h1>Lista de Productos</h1>
    <a href="index.php">Atras</a>

    <table>
        <tr>
            <th>Cod.</th>
            <th>Nombre</th>
            <th>Costo</th>
            <?php
            // Agregar los nombres de las listas como encabezados
            foreach ($lists as $list) {
                echo "<th>" . $list['name_list'] . "</th>";
            }
            ?>
        </tr>
        <?php
        if ($result_products->num_rows > 0) {
            // Mostrar los datos de cada producto
            while($product = $result_products->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $product["id_product"] . "</td>";
                echo "<td>" . $product["desc_product"] . "</td>";
                echo "<td>$" . number_format($product["cost_product"], 2) . "</td>";

                // Para cada producto, multiplicar por el coeficiente de cada lista
                foreach ($lists as $list) {
                    $precio_lista = $product["cost_product"] * $list["coef_list"];
                    echo "<td>$" . number_format($precio_lista, 2) . "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . (3 + count($lists)) . "'>No hay productos disponibles</td></tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
