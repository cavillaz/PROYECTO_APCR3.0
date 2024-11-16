<?php
// Iniciar la sesión si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobación de Zonas Comunes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div id="content">
        <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

        <div class="container mt-5">
            <h1 class="mb-4">Aprobación de Zonas Comunes</h1>

            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Zona</th>
                        <th>Solicitante</th>
                        <th>Torre</th>
                        <th>Apartamento</th>
                        <th>Fecha Solicitada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td><?= htmlspecialchars($solicitud['nombre_zona']); ?></td>
                                <td><?= htmlspecialchars($solicitud['solicitante']); ?></td>
                                <td><?= htmlspecialchars($solicitud['torre']); ?></td>
                                <td><?= htmlspecialchars($solicitud['apartamento']); ?></td>
                                <td><?= htmlspecialchars($solicitud['fecha_solicitada']); ?></td>
                                <td><?= ucfirst(htmlspecialchars($solicitud['estado'])); ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="aprobarSolicitud(<?= $solicitud['id']; ?>)">
                                        <i class="fas fa-check-circle"></i> Aprobar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="rechazarSolicitud(<?= $solicitud['id']; ?>)">
                                        <i class="fas fa-times-circle"></i> Rechazar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay solicitudes pendientes de aprobación</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function aprobarSolicitud(id) {
            if (confirm('¿Estás seguro de que deseas aprobar esta solicitud?')) {
                $.ajax({
                    url: '/PROYECTO_APCR3.0/zonas_comunes/aprobar',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        alert('Solicitud aprobada correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al aprobar la solicitud');
                    }
                });
            }
        }

        function rechazarSolicitud(id) {
            if (confirm('¿Estás seguro de que deseas rechazar esta solicitud?')) {
                $.ajax({
                    url: '/PROYECTO_APCR3.0/zonas_comunes/rechazar',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        alert('Solicitud rechazada correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al rechazar la solicitud');
                    }
                });
            }
        }
    </script>
</body>
</html>
