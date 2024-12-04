<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Parqueaderos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">
</head>
<body>

    <!-- Incluir barra lateral -->
    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div id="content">
        <!-- Incluir barra superior -->
        <?php require_once __DIR__ . '/../partials/navbar.php'; ?>


        <div class="container mt-5">
    <div class="text-center">
        <h1 class="mb-4 text-primary">Módulo de Parqueaderos</h1>
        <p class="lead">Gestione y administre los parqueaderos de forma sencilla y organizada.</p>
    </div>

    <div class="row text-center">
        <!-- Gestión de Parqueadero -->
        <?php if ($_SESSION['rol'] === 'residente' || $_SESSION['rol'] === 'porteria' || $_SESSION['rol'] === 'administrador'): ?>
            <div class="col-md-4">
                <a href="/PROYECTO_APCR3.0/parqueaderos/gestion" class="parking-card bg-primary">
                    <i class="fas fa-car"></i>
                    <h5>Gestión de Parqueadero</h5>
                </a>
            </div>
        <?php endif; ?>

        <!-- Aprobación de Parqueadero -->
        <?php if ($_SESSION['rol'] === 'porteria' || $_SESSION['rol'] === 'administrador'): ?>
            <div class="col-md-4">
                <a href="/PROYECTO_APCR3.0/parqueaderos/aprobacion" class="parking-card bg-primary">
                    <i class="fas fa-check-circle"></i>
                    <h5>Aprobación de Parqueadero</h5>
                </a>
            </div>
        <?php endif; ?>

        <!-- Historial de Parqueaderos -->
        <?php if ($_SESSION['rol'] === 'porteria' || $_SESSION['rol'] === 'administrador'): ?>
            <div class="col-md-4">
                <a href="/PROYECTO_APCR3.0/parqueaderos/historial" class="parking-card bg-primary">
                    <i class="fas fa-history"></i>
                    <h5>Historial de Parqueaderos</h5>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Script para expandir y contraer la barra lateral
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        });
    </script>
</body>
</html>
