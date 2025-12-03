<?php
$host = "127.0.0.1";       // o la IP del servidor
$user = "root";            // tu usuario MySQL
$pass = "";                // contraseña MySQL
$db   = "diplomas_litoral"; // tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Para caracteres especiales
$conn->set_charset("utf8");
?>
