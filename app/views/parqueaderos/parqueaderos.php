<?php
// Solo iniciar la sesión si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Parqueaderos</title>
    <!-- Incluye Bootstrap y Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">
</head>

<body>

    <!-- Incluir barra lateral -->
    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- Contenido principal -->
    <div id="content">
        <!-- Incluir barra superior -->
        <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

        <div class="container mt-5">
            <h1 class="mb-4">Módulo de Parqueaderos</h1>

            <div class="row">
                <!-- Botón de Gestión de Parqueadero (visible para todos los roles) -->
                <div class="col-md-4">
                    <a href="/PROYECTO_APCR3.0/parqueaderos/gestion" class="btn btn-primary btn-block parking-button">
                        <i class="fas fa-car"></i> Gestión de Parqueadero
                    </a>
                </div>

                <!-- Botón de Aprobación de Parqueadero (solo visible para administrador y portería) -->
                <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'porteria')): ?>
                <div class="col-md-4">
                    <a href="/PROYECTO_APCR3.0/parqueaderos/aprobacion" class="btn btn-success btn-block parking-button">
                        <i class="fas fa-check"></i> Aprobación de Parqueadero
                    </a>
                </div>
                <?php endif; ?>

                <!-- Botón de Historial de Parqueaderos (visible para todos los roles) -->
                <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'administrador' || $_SESSION['rol'] == 'porteria')): ?>
                <div class="col-md-4">
                    
                    <a href="/PROYECTO_APCR3.0/parqueaderos/historial" class="btn btn-info btn-block parking-button">
                        <i class="fas fa-history"></i> Historial de Parqueaderos
                    </a>
                </div>
                <?php endif; ?>
            </div>
            </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Script para expandir y contraer la barra lateral
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        });

        // Mostrar modal de mensaje si existe un mensaje en la URL
        $(document).ready(function() {
            <?php if (isset($_GET['mensaje'])): ?>
                $('#mensajeModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>
