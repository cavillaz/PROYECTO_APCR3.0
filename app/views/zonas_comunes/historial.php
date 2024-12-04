<?php
// Solo iniciar la sesión si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../partials/auth.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Zonas Comunes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/styles.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div id="content">
        <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

        <div class="container mt-5">
            <h1 class="text-center mb-4">Historial de Zonas Ocupadas</h1>

            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Zona</th>
                        <th>Solicitante</th>
                        <th>Torre</th>
                        <th>Apartamento</th>
                        <th>Fecha Solicitada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($zonasOcupadas)): ?>
                        <?php foreach ($zonasOcupadas as $zona): ?>
                            <tr>
                                <td><?= htmlspecialchars($zona['nombre_zona']); ?></td>
                                <td><?= htmlspecialchars($zona['solicitante']); ?></td>
                                <td><?= htmlspecialchars($zona['torre']); ?></td>
                                <td><?= htmlspecialchars($zona['apartamento']); ?></td>
                                <td><?= htmlspecialchars($zona['fecha_solicitada']); ?></td>
                                <td class="text-success"><?= ucfirst(htmlspecialchars($zona['estado'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay zonas ocupadas actualmente</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
