<?php
include 'dbconnect.php';

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta corregida
$query = "SELECT m.*, p.desc_product, d1.nombre AS origen, d2.nombre AS destino 
          FROM movimientos_stock m
          JOIN product p ON m.id_producto = p.id_product
          JOIN depositos d1 ON m.id_deposito_origen = d1.id
          JOIN depositos d2 ON m.id_deposito_destino = d2.id
          ORDER BY STR_TO_DATE(m.fecha, '%Y-%m-%d %H:%i:%s') DESC"; 

$result = mysqli_query($conn, $query);

// Verificar si hay errores en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Movimientos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .container {
            background-color: #800000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
            max-width: 800px;
            margin: auto;
        }

        h1 {
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            color: black;
        }

        th {
            background-color: #800000;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .back-button {
            display: inline-block;
            background-color: rgb(204, 204, 204);
            color: #800000;
            padding: 10px 20px;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-button:hover {
            background-color: #ffff11;
            color: #ffd700;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Historial de Movimientos</h1>

    <table>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Desde</th>
            <th>Hacia</th>
            <th>Cantidad</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= htmlspecialchars($row['fecha']); ?></td>
                <td><?= htmlspecialchars($row['desc_product']); ?></td>
                <td><?= htmlspecialchars($row['origen']); ?></td>
                <td><?= htmlspecialchars($row['destino']); ?></td>
                <td><?= htmlspecialchars($row['cantidad']); ?></td>
            </tr>
        <?php endwhile; ?>

    </table>

    <a href="index.php" class="back-button">Volver</a>
</div>

</body>
</html>
