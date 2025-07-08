<?php
require 'dbconnect.php';

// Filtros
$order = "desc_product"; // Por defecto, ordena por nombre
$allowed_orders = ["id_product", "desc_product", "cost_product"];

if (isset($_GET['orden']) && in_array($_GET['orden'], $allowed_orders)) {
    $order = $_GET['orden'];
}

// Consulta productos
$query = "SELECT id_product, desc_product, cost_product FROM product ORDER BY $order";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }
        h1 {
            text-align: center;
        }
        .buscador {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
            font-size: 16px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px #ccc;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th a {
            color: white;
            text-decoration: none;
        }
        th {
            background-color: #007acc;
        }
        .btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text: white;
            text-decoration: none;
        }
        .cotizacion {
            background-color: #28a745;
            color: white;
            text: white;
            text-decoration: none;
        }
        .stock {
            background-color: #17a2b8;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .container a {
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

        .container a:hover {
            background-color: #ffff11; /* Cambia el color de fondo al pasar el cursor */
            color: #ffd700; /* Cambia el texto a dorado */
        }
    </style>
    <script>
        function filtrarTabla() {
            const input = document.getElementById("filtro");
            const filtro = input.value.toLowerCase();
            const filas = document.querySelectorAll("#tablaProductos tbody tr");

            filas.forEach(fila => {
                const textoFila = fila.innerText.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? "" : "none";
            });
        }
    </script>
</head>
<body>

<h1>Lista de Productos</h1>


<div class="buscador">
    <input type="text" id="filtro" placeholder="Buscar por nombre o código..." onkeyup="filtrarTabla()"> 
    <button class="btn cotizacion"><a href="index.php">Atrás</a></button>
</div>

<table id="tablaProductos">
    <thead>
        <tr>
            <th><a href="?orden=id_product">ID</a></th>
            <th><a href="?orden=desc_product">Producto</a></th>
            <th><a href="?orden=cost_product">Precio Base</a></th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_product'] ?></td>
                    <td><?= $row['desc_product'] ?></td>
                    <td>$<?= number_format($row['cost_product'], 2, ',', '.') ?></td>
                    <td>
                        <form action="cotizador.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id_product" value="<?= $row['id_product'] ?>">
                            <button type="button" onclick="mostrarCotizaciones(<?= $producto['id_product'] ?>, this)">Ver Cotizaciones</button>
<div class="cotizaciones" style="display:none; margin-top:10px;"></div>

                        </form>
                        <form action="ver_stock.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id_product" value="<?= $row['id_product'] ?>">
                            <button class="btn stock">Ver Stock</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No hay productos cargados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<script>
function mostrarCotizaciones(id, btn) {
    const container = btn.nextElementSibling;

    // Si ya está visible, ocultalo
    if (container.style.display === "block") {
        container.style.display = "none";
        container.innerHTML = '';
        return;
    }

    fetch(`cotizaciones_api.php?id_product=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                container.innerHTML = "<p>No se encontraron cotizaciones.</p>";
            } else {
                let html = "<table border='1' style='width:100%; margin-top:10px;'>";
                html += "<tr><th>Lista</th><th>Coeficiente</th><th>Precio Final</th></tr>";
                data.forEach(row => {
                    html += `<tr>
                        <td>${row.lista}</td>
                        <td>${row.coef}</td>
                        <td>$${row.precio}</td>
                    </tr>`;
                });
                html += "</table>";
                container.innerHTML = html;
            }
            container.style.display = "block";
        })
        .catch(err => {
            container.innerHTML = "<p>Error al cargar cotizaciones.</p>";
            container.style.display = "block";
        });
}
</script>

</body>
</html>

<?php $conn->close(); ?>
