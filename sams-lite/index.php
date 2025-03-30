<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaMS - Lite</title>
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
            background-color: #800000; /* Color bordó estilo poncho salteño */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            text: white;
        }
        h1 {
            color: white;
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
</head>
<body>
    <div class="container">
        <h1>Sales Managment System</h1>
        <a href="ventas.php">Ventas</a>
        <a href="cotizador.php">Cotizador</a>
        <a href="mover_stock.php">Movimientos</a>
        <a href="historial_movimientos.php">Ver Movimientos</a>
        <a href="cotizadorcfg.php">Configurar Cotizador</a>
        <a href="clientes.php">Clientes</a>
    </div>
</body>
</html>
