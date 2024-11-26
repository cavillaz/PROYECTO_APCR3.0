<?php
// Verificar si la sesión ya está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: /PROYECTO_APCR3.0/usuarios/login?mensaje=Por%20favor%20inicia%20sesión.");
    exit();
}
?>
