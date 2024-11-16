<?php
// Solo iniciar la sesión si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
require_once __DIR__ . '/../../../config/database.php';

// Obtener los datos del historial de parqueaderos
$query = "SELECT * FROM tb_historial_parqueaderos";
$result = $conn->query($query);
$historial = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Parqueaderos</title>
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
            <h1>Historial de Parqueaderos</h1>

            <!-- Filtros por fecha -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="fechaSolicitud">Fecha de Solicitud</label>
                    <input type="date" id="fechaSolicitud" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="fechaLiberacion">Fecha de Liberación</label>
                    <input type="date" id="fechaLiberacion" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <!-- Botón de filtrar con ícono -->
                    <button id="btnFiltrar" class="btn btn-primary btn-icon mr-2">
                        <i class="fas fa-filter"></i>
                    </button>
                    <!-- Botón de exportar XLSX con ícono -->
                    <button id="btnExportarXLSX" class="btn btn-success btn-icon">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </div>
            </div>

            <table class="table table-bordered mt-3" id="tablaHistorial">
                <thead class="thead-dark">
                    <tr>
                        <th>Parqueadero</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Tipo Vehículo</th>
                        <th>Placa</th>
                        <th>Tipo Parqueadero</th>
                        <th>Pago</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Liberación</th>
                        <th>Valor Pagado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $solicitud): ?>
                        <tr>
                            <td><?= $solicitud['parqueadero_id']; ?></td>
                            <td><?= $solicitud['nombre_persona']; ?></td>
                            <td><?= $solicitud['documento_persona']; ?></td>
                            <td><?= ucfirst($solicitud['tipo_vehiculo']); ?></td>
                            <td><?= $solicitud['placa_vehiculo']; ?></td>
                            <td><?= ucfirst($solicitud['tipo_parqueadero']); ?></td>
                            <td><?= $solicitud['valor_pagado'] > 0 ? 'Sí' : 'No'; ?></td>
                            <td><?= $solicitud['fecha_solicitud']; ?></td>
                            <td><?= $solicitud['fecha_liberacion'] ?? 'N/A'; ?></td>
                            <td><?= $solicitud['valor_pagado'] ?? 'N/A'; ?></td>
                            <td><?= ucfirst($solicitud['estado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts de Bootstrap, jQuery y SheetJS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function () {
            // Filtrar la tabla por fecha
            $('#btnFiltrar').on('click', function () {
                var fechaSolicitud = $('#fechaSolicitud').val();
                var fechaLiberacion = $('#fechaLiberacion').val();

                $('#tablaHistorial tbody tr').each(function () {
                    var solicitud = $(this).find('td:eq(7)').text().split(' ')[0]; // Fecha de solicitud
                    var liberacion = $(this).find('td:eq(8)').text().split(' ')[0]; // Fecha de liberación

                    var mostrar = true;

                    if (fechaSolicitud && solicitud !== fechaSolicitud) {
                        mostrar = false;
                    }
                    if (fechaLiberacion && liberacion !== fechaLiberacion) {
                        mostrar = false;
                    }

                    $(this).toggle(mostrar);
                });
            });

            // Exportar a XLSX
            $('#btnExportarXLSX').on('click', function () {
                var data = [];
                $('#tablaHistorial thead tr').each(function () {
                    var row = [];
                    $(this).find('th').each(function () {
                        row.push($(this).text());
                    });
                    data.push(row);
                });

                $('#tablaHistorial tbody tr:visible').each(function () {
                    var row = [];
                    $(this).find('td').each(function () {
                        row.push($(this).text());
                    });
                    data.push(row);
                });

                var worksheet = XLSX.utils.aoa_to_sheet(data);
                var workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, 'Historial');

                XLSX.writeFile(workbook, 'historial_parqueaderos.xlsx');
            });
        });
    </script>

</body>
</html>
