<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administración de Usuarios</title>
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
