<?php
include("dbconnect.php");

// Verificar si se ha enviado el formulario para agregar un nuevo cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni_cuit_client = $_POST['dni_cuit_client'];
    $name_razon_social = $_POST['name_razon_social'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $vendedor = $_POST['vendedor'];

    // Insertar el nuevo cliente en la base de datos
    $sql_insert = "INSERT INTO clients (dni_cuit_client, name_razon_social, telefono, direccion, email, vendedor) VALUES ('$dni_cuit_client', '$name_razon_social', '$telefono', '$direccion', '$email', '$vendedor')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "<p>Cliente agregado exitosamente.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Consultar todos los clientes
$sql_clients = "SELECT dni_cuit_client, name_razon_social, telefono, direccion, email, vendedor FROM clients";
$result_clients = $conn->query($sql_clients);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - SaMS Lite</title>
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
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        select {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-submit {
            padding: 10px 20px;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #a00000;
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

    <h1>Gestión de Clientes</h1>
    <a href="index.php">Atrás</a>

    <!-- Formulario para agregar un nuevo cliente -->
    <div class="form-container">
        <h2>Agregar Nuevo Cliente</h2>
        <form action="clientes.php" method="POST">
            <label for="dni_cuit_client">DNI o CUIT del Cliente:</label>
            <input type="text" id="dni_cuit_client" name="dni_cuit_client" required>
            
            <label for="name_razon_social">Nombre Completo o Razón Social:</label>
            <input type="text" id="name_razon_social" name="name_razon_social" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">
            
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion">
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
            
            <label for="vendedor">Vendedor:</label>
            <select id="vendedor" name="vendedor" required>
                <option value="">Seleccionar Vendedor</option>
                <option value="Juan Guerra">Juan Guerra</option>
                <option value="Pablo Chavez">Pablo Chavez</option>
                <option value="Naira Guzman">Naira Guzman</option>
                <option value="Francisco Lizarraga">Francisco Lizarraga</option>
                <option value="Franco Gallo">Franco Gallo</option>
                <option value="Daiana Alanis">Daiana Alanis</option>
            </select>
            
            <button type="submit" class="btn-submit">Agregar Cliente</button>
        </form>
    </div>

    <!-- Tabla para mostrar los clientes -->
    <table>
        <tr>
            <th>DNI/CUIT</th>
            <th>Nombre Completo / Razón Social</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Email</th>
            <th>Vendedor</th>
        </tr>
        <?php
        if ($result_clients->num_rows > 0) {
            // Mostrar los datos de cada cliente
            while($client = $result_clients->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $client["dni_cuit_client"] . "</td>";
                echo "<td>" . $client["name_razon_social"] . "</td>";
                echo "<td>" . $client["telefono"] . "</td>";
                echo "<td>" . $client["direccion"] . "</td>";
                echo "<td>" . $client["email"] . "</td>";
                echo "<td>" . $client["vendedor"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay clientes registrados</td></tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
