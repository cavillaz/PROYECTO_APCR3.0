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

    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div id="content">
        <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

        <div class="container mt-5">
            <h1 class="mb-4">Módulo de Zonas Comunes</h1>

            <div class="row">
                <div class="col-md-4">
                    <a href="/PROYECTO_APCR3.0/zonas_comunes/solicitud" class="btn btn-solicitud zona-boton">
                        <i class="fas fa-calendar-plus"></i> Solicitud Zonas Comunes
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/PROYECTO_APCR3.0/zonas_comunes/aprobacion" class="btn btn-aprobacion zona-boton">
                        <i class="fas fa-check-circle"></i> Aprobación de Zonas
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/PROYECTO_APCR3.0/zonas_comunes/historial" class="btn btn-historial zona-boton">
                        <i class="fas fa-history"></i> Historial de Zonas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        });

        $(document).ready(function() {
            <?php if (isset($_GET['mensaje'])): ?>
                $('#mensajeModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>
