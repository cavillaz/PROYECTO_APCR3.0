<?php
$host = '10.128.0.3';
$usuario = 'apcr';
$password = 'root';
$base_datos = 'proyecto_mvc';
$puerto = 3306;


global $conn;
$conn = new mysqli($host, $usuario, $password, $base_datos, $puerto);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
