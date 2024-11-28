<?php

// // # Conexión a la base de datos

// $servername = "localhost";
// $username = "root";  // Cambia por tu usuario de la base de datos
// $password = "";  // Cambia por tu contraseña
// $dbname = "sams-cotizador";

$servername = "localhost";
$username = "c2611613";  // Cambia por tu usuario de la base de datos
$password = "SI42dakize";  // Cambia por tu contraseña
$dbname = "c2611613_devjmr";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


?>