<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Zonas Comunes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">
    <style>
        /* Tus estilos de diseño */
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

<div id="content">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Solicitud de Zonas Comunes</h1>

        <div class="d-flex justify-content-around">
            <button class="zona-boton" data-zona="Salón Comunal">Salón Comunal</button>
            <button class="zona-boton" data-zona="Zona BBQ">Zona BBQ</button>
            <button class="zona-boton" data-zona="Zona de Juegos">Zona de Juegos</button>
            <button class="zona-boton" data-zona="Cancha Fútbol">Cancha Fútbol</button>
        </div>

        <div class="calendar-container" style="display: none; margin-top: 20px;">
            <div class="month-navigation">
                <button id="prevMonth" class="btn btn-secondary">Anterior</button>
                <h2 id="calendarTitle"></h2>
                <button id="nextMonth" class="btn btn-secondary">Siguiente</button>
            </div>
            <div class="weekdays d-flex justify-content-between">
                <div>Lunes</div>
                <div>Martes</div>
                <div>Miércoles</div>
                <div>Jueves</div>
                <div>Viernes</div>
                <div>Sábado</div>
                <div>Domingo</div>
            </div>
            <div id="calendario" class="calendar"></div>
        </div>
    </div>
</div>

<!-- Modal para solicitud -->
<div class="modal fade" id="solicitudModal" tabindex="-1" role="dialog" aria-labelledby="solicitudModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="solicitudModalLabel">Solicitud de Zona Común</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSolicitud">
                    <input type="hidden" id="zona" name="zona">
                    <div class="form-group">
                        <label for="solicitante">Nombre del Solicitante</label>
                        <input type="text" class="form-control" value="<?= $_SESSION['usuario']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="torre">Torre</label>
                        <input type="text" class="form-control" value="<?= $_SESSION['torre']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="apartamento">Apartamento</label>
                        <input type="text" class="form-control" value="<?= $_SESSION['apartamento']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="fechaSolicitud">Fecha de Solicitud</label>
                        <input type="text" class="form-control" id="fechaSolicitud" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar Solicitud</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedZona = '';
    $(document).ready(function () {
    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedZona = '';

    $('.zona-boton').on('click', function () {
        selectedZona = $(this).data('zona');
        $('#zona').val(selectedZona);
        $('.calendar-container').fadeIn();
        renderCalendar();
    });

    // Navegar al mes anterior
    $('#prevMonth').on('click', function () {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--; // Cambiar al año anterior
        } else {
            currentMonth--;
        }
        renderCalendar();
    });

    $('#nextMonth').on('click', function () {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++; // Cambiar al año siguiente
        } else {
            currentMonth++;
        }
        renderCalendar();
    });

        $('#formSolicitud').on('submit', function(e) {
            e.preventDefault();

            const fechaSolicitada = $('#fechaSolicitud').val();

            $.ajax({
                url: '/PROYECTO_APCR3.0/zonas_comunes/realizarSolicitud',
                type: 'POST',
                data: {
                    zona: selectedZona,
                    fecha_solicitud: new Date().toISOString().split('T')[0],
                    fecha_solicitada: fechaSolicitada,
                    solicitante: '<?= $_SESSION['usuario'] ?>',
                    torre: '<?= $_SESSION['torre'] ?>',
                    apartamento: '<?= $_SESSION['apartamento'] ?>'
                },
                success: function(response) {
                    const res = JSON.parse(response);
                    alert(res.message);
                    if (res.success) {
                        renderCalendar(); // Recarga el calendario para reflejar los cambios
                        $('#solicitudModal').modal('hide');
                    }
                },
                error: function() {
                    alert('Error al enviar la solicitud.');
                }
            });
        });


        function renderCalendar() {
    $('#calendarTitle').text(`${months[currentMonth]} ${currentYear}`);
    $('#calendario').empty();

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Llama al servidor para obtener el estado de los días del mes
    $.ajax({
        url: '/PROYECTO_APCR3.0/zonas_comunes/obtenerEstadoZonas',
        type: 'POST',
        data: { zona: selectedZona, mes: currentMonth + 1, anio: currentYear },
        success: function(response) {
            const estados = JSON.parse(response);

            // Genera espacios en blanco antes del primer día del mes
            for (let i = 0; i < firstDay; i++) {
                $('#calendario').append('<div></div>');
            }

            // Renderiza los días del mes con el estado correcto
            for (let day = 1; day <= daysInMonth; day++) {
                const estado = estados[day] || 'free'; // Usa 'free' como estado por defecto
                let statusClass = 'free'; // Clase por defecto

                if (estado === 'ocupado') {
                    statusClass = 'occupied';
                } else if (estado === 'pendiente_aprobacion') {
                    statusClass = 'pending';
                }

                const dayElement = $('<div class="calendar-day"></div>')
                    .append(`<span>${day}</span>`)
                    .append(`<div class="status-circle ${statusClass}"></div>`);
                
                dayElement.on('click', function () {
                    $('#fechaSolicitud').val(`${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
                    $('#solicitudModal').modal('show');
                });
                
                $('#calendario').append(dayElement);
            }
        },
        error: function() {
            console.error("Error al cargar los estados del calendario.");
        }
    });


}


});
</script>
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
