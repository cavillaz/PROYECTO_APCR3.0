<?php
// Solo iniciar la sesión si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
require_once __DIR__ . '/../../../config/database.php';

// Obtener los parqueaderos
$query = "SELECT * FROM tb_parqueaderos";
$result = $conn->query($query);
$parqueaderos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Parqueadero</title>
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
            <h1>Gestión de Parqueadero</h1>
            <?php if (isset($_GET['mensaje'])): ?>
            <div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="mensajeModalLabel">
                        <?= isset($_GET['tipo']) && $_GET['tipo'] == 'success' ? 'Éxito' : 'Error'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?= urldecode($_GET['mensaje']); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
        <?php foreach ($parqueaderos as $parqueadero): 
        // Determinar la clase CSS basada en el estado del parqueadero
        $claseEstado = '';
        switch ($parqueadero['estado']) {
            case 'libre':
                $claseEstado = 'libre';
                break;
            case 'pendiente_aprobacion':
                $claseEstado = 'pendiente'; // Clase para el estado pendiente
                break;
            case 'ocupado':
                $claseEstado = 'ocupado';
                break;
            default:
                $claseEstado = 'libre'; // Default para evitar errores
                break;
        }
    ?>
        <div class="col-md-3 mb-4">
            <button class="btn parking-button <?= $claseEstado ?>" data-toggle="modal" data-target="#modalParqueadero" 
                data-id="<?= $parqueadero['id']; ?>" 
                data-numero="<?= $parqueadero['numero_parqueadero']; ?>"
                data-nombre="<?= $parqueadero['nombre_parqueadero']; ?>">
                <i class="fas fa-car"></i> Parqueadero <?= $parqueadero['numero_parqueadero']; ?>
            </button>
        </div>
    <?php endforeach; ?>
</div>


<!-- Modal para solicitar parqueadero -->
<div class="modal fade" id="modalParqueadero" tabindex="-1" role="dialog" aria-labelledby="modalParqueaderoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalParqueaderoLabel">Solicitar Parqueadero</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSolicitudParqueadero" action="/PROYECTO_APCR3.0/parqueaderos/solicitar" method="POST">
                    <input type="hidden" id="parqueadero_id" name="parqueadero_id">
                    <div class="form-group">
                        <label for="nombre_persona">Nombre de la persona</label>
                        <input type="text" class="form-control" id="nombre_persona" name="nombre_persona" required>
                    </div>
                    <div class="form-group">
                        <label for="documento_persona">Documento de la persona</label>
                        <input type="text" class="form-control" id="documento_persona" name="documento_persona" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_vehiculo">Tipo de vehículo</label>
                        <select class="form-control" id="tipo_vehiculo" name="tipo_vehiculo" required>
                            <option value="carro">Carro</option>
                            <option value="moto">Moto</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="placa_vehiculo">Placa de vehículo</label>
                        <input type="text" class="form-control" id="placa_vehiculo" name="placa_vehiculo" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_parqueadero">Tipo de parqueadero</label>
                        <select class="form-control" id="tipo_parqueadero" name="tipo_parqueadero" required>
                            <option value="residente">Residente</option>
                            <option value="visitante">Visitante</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Reservar Parqueadero</button>
                </form>
            </div>
        </div>
    </div>
</div>




    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#modalParqueadero').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que abrió el modal
            var id = button.data('id'); // Obtener el ID del parqueadero
            var modal = $(this);

            // Hacer una solicitud AJAX para obtener los datos del parqueadero
            $.ajax({
                url: '/PROYECTO_APCR3.0/parqueaderos/obtenerDatosParqueadero',
                type: 'POST',
                data: { parqueadero_id: id },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data) {
                        // Si el parqueadero está ocupado o pendiente de aprobación, mostrar los datos y deshabilitar los campos
                        modal.find('#nombre_persona').val(data.nombre_persona).prop('disabled', true);
                        modal.find('#documento_persona').val(data.documento_persona).prop('disabled', true);
                        modal.find('#tipo_vehiculo').val(data.tipo_vehiculo).prop('disabled', true);
                        modal.find('#placa_vehiculo').val(data.placa_vehiculo).prop('disabled', true);
                        modal.find('#tipo_parqueadero').val(data.tipo_parqueadero).prop('disabled', true);
                        modal.find('#pago').val(data.pago).prop('disabled', true);

                        // Mostrar mensaje de estado y ocultar botón de reservar
                        modal.find('#estadoMensaje').text('El parqueadero está en estado ' + data.estado).show();
                        modal.find('#reservarBtn').hide();
                    } else {
                        // Si está libre, habilitar los campos para la reserva
                        modal.find('#nombre_persona, #documento_persona, #tipo_vehiculo, #placa_vehiculo, #tipo_parqueadero, #pago').val('').prop('disabled', false);
                        modal.find('#estadoMensaje').hide();
                        modal.find('#reservarBtn').show();

                        // Cambiar el título del modal
                        modal.find('.modal-title').text('Reservar Parqueadero ' + id);
                    }

                    // Rellenar el ID del parqueadero en el campo oculto
                    modal.find('#parqueadero_id').val(id);
                },
                error: function() {
                    alert('Error al obtener los datos del parqueadero.');
                }
            });
        });



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

                $(document).ready(function() {
            <?php if (isset($_GET['mensaje'])): ?>
                $('#mensajeModal').modal('show');
            <?php endif; ?>
        });

    </script>
</body>
</html>
