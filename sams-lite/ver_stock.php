<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sams-cotizador";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obtener el stock de productos por depósito
$query = 'SELECT s.id, p.desc_product, dp.nombre AS desc_deposit, s.cantidad 
          FROM stock s
          JOIN product p ON s.id_producto = p.id_product
          JOIN depositos dp ON s.id_deposito = dp.id
          ORDER BY p.desc_product, dp.nombre';

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Stock</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: left;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Ver Stock de Productos por Depósito</h2>

<table>
    <thead>
        <tr>
            <th>ID Stock</th>
            <th>Producto</th>
            <th>Depósito</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Mostrar los resultados de la consulta
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['desc_product']}</td>
                        <td>{$row['desc_deposit']}</td>
                        <td>{$row['cantidad']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay productos en stock.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
