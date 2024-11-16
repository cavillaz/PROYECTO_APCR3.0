<?php
$host = 'localhost';
$usuario = 'root';
$password = '';  // o la contraseña si tiene
$base_datos = 'proyecto_mvc';
$puerto = 3307; // El puerto de MySQL, cámbialo si estás usando uno diferente

$conn = new mysqli($host, $usuario, $password, $base_datos, $puerto);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
