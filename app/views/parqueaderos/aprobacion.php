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
    <title>Aprobación de Parqueaderos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <!-- Incluir barra lateral -->
    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- Contenido principal -->
             <!-- Incluir barra superior -->
             <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mt-5">
        <h1>Aprobación y Liberación de Parqueaderos</h1>
    <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Parqueadero</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Tipo Vehículo</th>
                    <th>Placa</th>
                    <th>Tipo</th>
                    <th>Fecha Solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $solicitud): ?>
                    <tr>
                        <td><?= $solicitud['numero_parqueadero']; ?></td>
                        <td><?= $solicitud['nombre_persona']; ?></td>
                        <td><?= $solicitud['documento_persona']; ?></td>
                        <td><?= ucfirst($solicitud['tipo_vehiculo']); ?></td>
                        <td><?= $solicitud['placa_vehiculo']; ?></td>
                        <td><?= ucfirst($solicitud['tipo_parqueadero']); ?></td>
                        <td><?= $solicitud['fecha_solicitud']; ?></td>
                        <td>
                            <?php if ($solicitud['estado'] === 'pendiente_aprobacion'): ?>
                                <button onclick="aprobar(<?= $solicitud['id']; ?>)" class="btn btn-success btn-sm">Aceptar</button>
                                <button onclick="rechazar(<?= $solicitud['id']; ?>)" class="btn btn-danger btn-sm">Rechazar</button>
                            <?php elseif ($solicitud['estado'] === 'ocupado'): ?>
                                <button type="button" class="btn btn-warning btn-sm btn-liberar" data-id="<?= $solicitud['parqueadero_id']; ?>">Liberar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!-- Modal para ingresar el valor pagado -->
<div class="modal fade" id="modalLiberar" tabindex="-1" role="dialog" aria-labelledby="modalLiberarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLiberarLabel">Ingresar Valor Pagado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formLiberarParqueadero" method="POST" action="/PROYECTO_APCR3.0/parqueaderos/liberar">
                    <input type="hidden" id="parqueadero_id_liberar" name="parqueadero_id">
                    <div class="form-group">
                        <label for="valor_pagado">Valor Pagado</label>
                        <!-- Botón de Información -->
                        <button type="button" class="btn btn-info btn-sm" onclick="mostrarInfoResidente()">
                        <i class="fas fa-info-circle"></i>
                         </button> 
                        <input type="number" class="form-control" id="valor_pagado" name="valor_pagado" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Liberar Parqueadero</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Información para Residentes -->
<div class="modal fade" id="modalInfoResidente" tabindex="-1" role="dialog" aria-labelledby="modalInfoResidenteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoResidenteLabel">Información</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Si vas a liberar un parqueadero asignado a un residente, el valor a pagar debe ser $0.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Aprobación -->
<div class="modal fade" id="modalAprobar" tabindex="-1" role="dialog" aria-labelledby="modalAprobarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAprobarLabel">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas aprobar esta solicitud?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnConfirmarAprobar" class="btn btn-success">Aprobar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Rechazo -->
<div class="modal fade" id="modalRechazar" tabindex="-1" role="dialog" aria-labelledby="modalRechazarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRechazarLabel">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas rechazar esta solicitud?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnConfirmarRechazar" class="btn btn-danger">Rechazar</button>
            </div>
        </div>
    </div>
</div>

    <!-- Asegúrate de que estos archivos CSS están bien referenciados -->
     <!-- Incluye jQuery antes de usarlo -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">

        <script>
            function aprobar(id) {
            solicitudId = id; // Guardamos el ID de la solicitud
             $('#modalAprobar').modal('show'); // Mostramos el modal de aprobación
            }

            function rechazar(id) {
                solicitudId = id; // Guardamos el ID de la solicitud
                $('#modalRechazar').modal('show'); // Mostramos el modal de rechazo
            }

            // Confirmar la aprobación
            $('#btnConfirmarAprobar').on('click', function() {
                window.location.href = '/PROYECTO_APCR3.0/parqueaderos/aprobar?id=' + solicitudId;
            });

            // Confirmar el rechazo
            $('#btnConfirmarRechazar').on('click', function() {
                window.location.href = '/PROYECTO_APCR3.0/parqueaderos/rechazar?id=' + solicitudId;
            });
            function abrirModalLiberar(parqueaderoId) {
                $('#parqueadero_id_liberar').val(parqueaderoId); // Asigna el ID del parqueadero
                $('#modalLiberar').modal('show'); // Muestra el modal
            }

            function mostrarInfoResidente() {
                $('#modalInfoResidente').modal('show'); // Muestra el modal de información
            }
        </script>
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
    <script>
        $(document).ready(function() {
            $('.btn-liberar').on('click', function() {
                var parqueaderoId = $(this).data('id');
                $('#parqueadero_id_liberar').val(parqueaderoId);
                $('#modalLiberar').modal('show');
            });
        });

    </script>

    <script>
        function mostrarInformacion() {
            alert("Si vas a liberar un parqueadero asignado a un residente, el valor a pagar debe ser $0.");
        }
    </script>

</body>
</html>
