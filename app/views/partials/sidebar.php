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

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador'): ?>
        <a href="/PROYECTO_APCR3.0/usuarios/mostrar" class="menu-item">
            <i class="fas fa-users-cog"></i>
            <span>Administración de Usuarios</span>
        </a>
    <?php endif; ?>
</div>
