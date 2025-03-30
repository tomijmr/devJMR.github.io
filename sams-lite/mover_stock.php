<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mover Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #800000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: white;
        }

        h1 {
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 18px;
            margin-top: 10px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
        }

        button {
            background-color: #ffffff;
            color: #800000;
            text-decoration: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #ffff11;
            color: #ffd700;
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
    <script>
        function filtrarProductos() {
            let filtro = document.getElementById("buscar_producto").value.toLowerCase();
            let opciones = document.getElementById("select_producto").options;
            for (let i = 0; i < opciones.length; i++) {
                let texto = opciones[i].text.toLowerCase();
                if (texto.includes(filtro)) {
                    opciones[i].style.display = "block";
                } else {
                    opciones[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Mover Stock</h1>
        <form action="procesar_movimiento.php" method="POST">
            <label>Buscar Producto:</label>
            <input type="text" id="buscar_producto" onkeyup="filtrarProductos()" placeholder="Buscar por código o descripción">
            
            <label>Producto:</label>
            <select name="id_producto" id="select_producto" required>
                <?php
                include 'dbconnect.php';
                $productos = mysqli_query($conn, "SELECT id_product, desc_product FROM product");
                while ($row = mysqli_fetch_assoc($productos)) {
                    echo "<option value='{$row['id_product']}'>{$row['id_product']} - {$row['desc_product']}</option>";
                }
                ?>
            </select>

            <label>Depósito de Origen:</label>
            <select name="id_deposito_origen" required>
                <?php
                $depositos = mysqli_query($conn, "SELECT * FROM depositos");
                while ($row = mysqli_fetch_assoc($depositos)) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>

            <label>Depósito de Destino:</label>
            <select name="id_deposito_destino" required>
                <?php
                $depositos = mysqli_query($conn, "SELECT * FROM depositos");
                while ($row = mysqli_fetch_assoc($depositos)) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>

            <label>Cantidad a Mover:</label>
            <input type="number" name="cantidad" required min="1">

            <button type="submit">Mover Stock</button>
            <a href="index.php">Atras</a>
            
        </form>
    </div>
</body>
</html>
