<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Zonas Comunes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">
</head>
<body>

    <!-- Barra Lateral -->
    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div id="content">
        <!-- Barra Superior -->
        <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

        <!-- Contenido Principal -->
        <div class="container mt-5">
            <div class="text-center">
                <h1 class="mb-4 text-primary">Módulo de Zonas Comunes</h1>
                <p class="lead">Gestione y administre las zonas comunes de forma sencilla y organizada.</p>
            </div>

            <div class="row justify-content-center mt-5">
                <!-- Solicitud de Zonas Comunes -->
                <?php if ($rol === 'administrador' || $rol === 'porteria' || $rol === 'residente'): ?>
                    <div class="col-md-4 mb-4">
                        <a href="/PROYECTO_APCR3.0/zonas_comunes/solicitud" class="zona-card text-center">
                            <i class="fas fa-calendar-plus"></i>
                            <h5 class="mt-3">Solicitud de Zonas</h5>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Aprobación de Zonas -->
                <?php if ($rol === 'administrador'): ?>
                    <div class="col-md-4 mb-4">
                        <a href="/PROYECTO_APCR3.0/zonas_comunes/aprobacion" class="zona-card text-center">
                            <i class="fas fa-check-circle"></i>
                            <h5 class="mt-3">Aprobación de Zonas</h5>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Historial de Zonas -->
                <?php if ($rol === 'administrador' || $rol === 'porteria'): ?>
                    <div class="col-md-4 mb-4">
                        <a href="/PROYECTO_APCR3.0/zonas_comunes/historial" class="zona-card text-center">
                            <i class="fas fa-history"></i>
                            <h5 class="mt-3">Historial de Zonas</h5>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        });
    </script>
</body>
</html>
