<?php
$host = 'localhost';
$usuario = 'root';
$password = ''; 
$base_datos = 'base_apcr';
$puerto = 3306; 

$conn = new mysqli($host, $usuario, $password, $base_datos, $puerto);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>