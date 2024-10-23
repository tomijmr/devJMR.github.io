<?php
// login.php
session_start();

// Datos de conexión a la base de datos
$host = 'localhost'; // Cambia esto si es necesario
$dbname = 'c2611613_devjmr';
$username_db = 'c2611613'; // Reemplaza con tu usuario de base de datos
$password_db = 'SI42dakize'; // Reemplaza con tu contraseña de base de datos

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Consulta para verificar el usuario
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user) {
            if ($password === $user['user_password']) {
                // Guardar el estado de sesión
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                // Redirigir a cotizador.php
                header("Location: sams-lite/cotizador.php");
                exit;
            } else {
                echo "Usuario o contraseña incorrectos.";
            }
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
