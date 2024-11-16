<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Incluye Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Incluye el archivo CSS global -->
    <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row no-gutters">
            <!-- Parte izquierda: Imagen (solo se mostrará en pantallas medianas y grandes) -->
            <div class="col-lg-6 d-none d-lg-block image-container" style="background-image: url('/PROYECTO_APCR3.0/public/images/tu-imagen.jpg');"></div>

            <!-- Parte derecha: Formulario (siempre visible) -->
            <div class="col-lg-6 col-md-12 form-container">
                <div class="form-box mx-auto">
                    <h3>Registro de Usuario</h3>
                    <form action="/PROYECTO_APCR3.0/usuarios/registrar" method="POST">
                        <div class="form-group">
                            <label for="correo">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="clave">Contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                        </div>
                        <div class="form-group">
                            <label for="numero_documento">Número de Documento</label>
                            <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
                        </div>
                        <div class="form-group">
                            <label for="numero_celular">Número de Celular</label>
                            <input type="text" class="form-control" id="numero_celular" name="numero_celular" required>
                        </div>
                        <!-- Select de torres -->
                        <div class="form-group">
                            <label for="torre">Torre</label>
                            <select class="form-control" id="torre" name="torre" required>
                                <option value="">Seleccione una torre</option>
                                <?php foreach ($torres as $torre): ?>
                                    <option value="<?= $torre['id'] ?>"><?= $torre['nombre_torre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Select de apartamentos -->
                        <div class="form-group">
                            <label for="apartamento">Apartamento</label>
                            <select class="form-control" id="apartamento" name="apartamento" required>
                                <option value="">Seleccione un apartamento</option>
                                <?php foreach ($apartamentos as $apartamento): ?>
                                    <option value="<?= $apartamento['id'] ?>"><?= $apartamento['numero_apartamento'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                        <div class="text-center mt-3">
                            <a href="/PROYECTO_APCR3.0/usuarios/login" class="btn btn-secondary btn-block">Inicio</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana modal para mensajes -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= isset($_GET['mensaje']) ? $_GET['mensaje'] : ''; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Mostrar la ventana modal si hay un mensaje -->
    <script>
    $(document).ready(function(){
        <?php if (isset($_GET['mensaje'])): ?>
            $('#mensajeModal').modal('show');
        <?php endif; ?>
    });
    </script>
</body>
</html>
