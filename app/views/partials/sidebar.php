<?php
// Iniciar la sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$rol = $_SESSION['rol'] ?? null; // Obtener el rol del usuario desde la sesión
?>
<div id="sidebar">
    <button class="hamburger-btn" id="toggleSidebar">
        <i class="fas fa-bars"></i>
    </button>

    <a href="/PROYECTO_APCR3.0/usuarios/principal" class="menu-item">
        <i class="fas fa-home"></i>
        <span>Inicio</span>
    </a>

    <a href="/PROYECTO_APCR3.0/parqueaderos" class="menu-item">
        <i class="fas fa-parking"></i>
        <span>Parqueaderos</span>
    </a>

    <a href="/PROYECTO_APCR3.0/zonas_comunes" class="menu-item">
        <i class="fas fa-building"></i>
        <span>Zonas Comunes</span>
    </a>

    <!-- Mostrar solo si el rol es administrador -->
    <?php if ($rol === 'administrador'): ?>
        <a href="/PROYECTO_APCR3.0/usuarios/mostrar" class="menu-item">
            <i class="fas fa-users-cog"></i>
            <span>Administración de Usuarios</span>
        </a>
    <?php endif; ?>
</div>
