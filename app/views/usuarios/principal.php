<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Página Principal</title>
  <!-- Incluye Bootstrap y Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <link rel="stylesheet" href="/PROYECTO_APCR3.0/public/css/styles.css">

</head>

<body>
<?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

<div id="content">
  <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

  <!-- Banner de Bienvenida -->
  <div class="container mt-4">
      <div class="banner">
          <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?>!</h1>
          <p class="lead">Accede rápidamente a las funcionalidades clave.</p>
      </div>
  </div>

  <!-- Accesos Rápidos -->
  <div class="container quick-access">
      <h2>Accesos Rápidos</h2>
      <div class="row">
          <?php if ($_SESSION['rol'] === 'administrador'): ?>
              <div class="col-md-4">
                  <div class="card bg-danger">
                      <div class="card-body">
                          <i class="fas fa-users-cog"></i>
                          <h5 class="card-title">Administración de Usuarios</h5>
                          <a href="/PROYECTO_APCR3.0/usuarios/mostrar" class="stretched-link"></a>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="card bg-primary">
                      <div class="card-body">
                          <i class="fas fa-check-circle"></i>
                          <h5 class="card-title">Aprobación Zonas</h5>
                          <a href="/PROYECTO_APCR3.0/zonas_comunes/aprobacion" class="stretched-link"></a>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="card bg-primary">
                      <div class="card-body">
                          <i class="fas fa-check-circle"></i>
                          <h5 class="card-title">Aprobación Parqueaderos</h5>
                          <a href="/PROYECTO_APCR3.0/parqueaderos/aprobacion" class="stretched-link"></a>
                      </div>
                  </div>
              </div>
          <?php endif; ?>
          <?php if ($_SESSION['rol'] === 'residente'): ?>
              <div class="col-md-4">
                  <div class="card bg-success">
                      <div class="card-body">
                          <i class="fas fa-calendar-plus"></i>
                          <h5 class="card-title">Solicitar Parqueadero</h5>
                          <a href="/PROYECTO_APCR3.0/parqueaderos/gestion" class="stretched-link"></a>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="card bg-success">
                      <div class="card-body">
                          <i class="fas fa-calendar-plus"></i>
                          <h5 class="card-title">Solicitar Zona</h5>
                          <a href="/PROYECTO_APCR3.0/zonas_comunes/solicitud" class="stretched-link"></a>
                      </div>
                  </div>
              </div>
          <?php endif; ?>
          <?php if ($_SESSION['rol'] === 'porteria'): ?>
              <div class="col-md-4">
                  <div class="card bg-info">
                      <div class="card-body">
                          <i class="fas fa-parking"></i>
                          <h5 class="card-title">Gestión Parqueaderos</h5>
                          <a href="/PROYECTO_APCR3.0/parqueaderos" class="stretched-link"></a>
                      </div>
                  </div>
              </div>
          <?php endif; ?>
      </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Script para expandir y contraer la barra lateral
    document.getElementById('toggleSidebar').addEventListener('click', function () {
      var sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('expanded');
    });
</script>
</body>
</html>
