<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/css/styles.css">

</head>
<body>
    <div class="container-fluid">
        <div class="row no-gutters">
            <!-- Lado de la imagen -->
            <div class="col-md-6 image-container" style="background-image: url('../../../public/images/logoapcr.png');"></div>
            
            <!-- Lado del formulario -->
            <div class="col-md-6 form-container">
                <div class="form-box">
                    <h3>Inicio de Sesión</h3>

                    <!-- Mensaje de alerta -->
                    <?php if (isset($_GET['mensaje'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_GET['mensaje']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de Login -->
                    <form action="/PROYECTO_APCR3.0/usuarios/validarLogin" method="POST">
                        <div class="form-group">
                            <label for="correo">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="clave">Contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                    </form>

                    <!-- Botón de Registro -->
                    <div class="text-center mt-3">
                        <a href="/PROYECTO_APCR3.0/usuarios/registro" class="btn btn-secondary btn-block">Registrar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
