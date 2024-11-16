<?php
// Iniciar la sesión si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Icono de perfil del usuario -->
    <a href="#" class="navbar-text text-white mx-2">
      <i class="fas fa-user-circle"></i> <!-- Ícono de perfil -->
    </a>
    
    <span class="navbar-text text-white mx-2">Bienvenido, <?= $_SESSION['usuario']; ?></span>
    
    <!-- Icono de cerrar sesión -->
    <a href="/PROYECTO_APCR3.0/usuarios/logout" class="navbar-text text-white mx-2">
      <i class="fas fa-sign-out-alt"></i> <!-- Ícono de cerrar sesión -->
    </a>
  </div>
</nav>
