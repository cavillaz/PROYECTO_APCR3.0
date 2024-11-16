<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<div class="container mt-5">
    <h1>Administración de Usuarios</h1>

    <!-- Botón para abrir el modal de agregar usuario -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalUsuario">
        <i class="fas fa-user-plus"></i> Agregar Usuario
    </button>

    <?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-danger">
        <?= urldecode($_GET['mensaje']); ?>
    </div>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Correo</th>
                <th>Número de Documento</th>
                <th>Nombre Completo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['correo']; ?></td>
                    <td><?= $usuario['numero_documento']; ?></td>
                    <td><?= $usuario['nombre_completo']; ?></td>
                    <td><?= $usuario['rol']; ?></td>
                    <td>
                        <!-- Botón de editar (icono) -->
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalUsuario" onclick="editarUsuario(<?= $usuario['numero_documento']; ?>)">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Botón de eliminar (icono) -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalEliminar" onclick="eliminarUsuario('<?= $usuario['numero_documento']; ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay usuarios registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Agregar/Editar Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioLabel">Agregar/Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario" action="/PROYECTO_APCR3.0/usuarios/guardar" method="POST">
                <input type="hidden" id="numero_documento_original" name="numero_documento_original" value="">
                <input type="hidden" id="correo_original" name="correo_original" value="<?= $usuario['correo']; ?>">
                <input type="hidden" id="torre_original" name="torre_original" 
                    value="<?= isset($usuario['id_torre']) ? $usuario['id_torre'] : ''; ?>">
                <input type="hidden" id="apartamento_original" name="apartamento_original" 
                    value="<?= isset($usuario['id_apartamento']) ? $usuario['id_apartamento'] : ''; ?>">


                    <!-- Campos del formulario -->
                    <div class="form-group">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
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
          <!-- Campo Rol -->
          <div class="form-group">
              <label for="rol">Rol</label>
              <select class="form-control" id="rol" name="rol" onchange="mostrarCamposResidente()" required>
                  <option value="administrador">Administrador</option>
                  <option value="porteria">Portería</option>
                  <option value="residente">Residente</option>
              </select>
          </div>

          <!-- Campos Torre y Apartamento solo para residentes -->
          <div id="camposResidente" style="display:none;">
              <div class="form-group">
                  <label for="torre">Torre</label>
                  <select class="form-control" id="torre" name="torre">
                      <option value="">Seleccione Torre</option>
                      <?php if (!empty($torres)): ?>
                          <?php foreach ($torres as $torre): ?>
                              <option value="<?= $torre['id']; ?>"><?= $torre['nombre_torre']; ?></option>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <option value="">No hay torres disponibles</option>
                      <?php endif; ?>
                  </select>
              </div>

              <div class="form-group">
                  <label for="apartamento">Apartamento</label>
                  <select class="form-control" id="apartamento" name="apartamento">
                      <option value="">Seleccione Apartamento</option>
                      <?php if (!empty($apartamentos)): ?>
                          <?php foreach ($apartamentos as $apartamento): ?>
                              <option value="<?= $apartamento['id']; ?>"><?= $apartamento['numero_apartamento']; ?></option>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <option value="">No hay apartamentos disponibles</option>
                      <?php endif; ?>
                  </select>
              </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" form="formUsuario" class="btn btn-primary">Guardar Usuario</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea eliminar este usuario?
                <!-- Campo oculto para almacenar el ID del usuario -->
                <input type="hidden" id="idUsuarioEliminar" name="idUsuarioEliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminar()">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Mostrar campos de torre y apartamento solo si el rol es residente
    function mostrarCamposResidente() {
        var rol = document.getElementById("rol").value;
        var camposResidente = document.getElementById("camposResidente");
        if (rol === "residente") {
            camposResidente.style.display = "block";
        } else {
            camposResidente.style.display = "none";
        }
    }

    // Función para pasar el ID del usuario al modal de eliminación
    function eliminarUsuario(idUsuario) {
        document.getElementById('idUsuarioEliminar').value = idUsuario;
    }

    // Función para confirmar y eliminar el usuario vía AJAX
    function confirmarEliminar() {
        var idUsuario = document.getElementById('idUsuarioEliminar').value;
        $.ajax({
            url: '/PROYECTO_APCR3.0/usuarios/eliminar',
            type: 'POST',
            dataType: 'json',
            data: { id: idUsuario },
            success: function(response) {
                if (response.success) {
                    window.location.href = "/PROYECTO_APCR3.0/usuarios/mostrar?mensaje=" + encodeURIComponent(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Error al procesar la solicitud. Verifica tu conexión.");
            }
        });
    }

    // Mostrar modal de mensaje si existe un mensaje en la URL
    $(document).ready(function() {
        <?php if (isset($_GET['mensaje'])): ?>
            $('#mensajeModal').modal('show');
        <?php endif; ?>
    });



    function editarUsuario(numeroDocumento) {
    $.ajax({
        url: '/PROYECTO_APCR3.0/usuarios/obtenerUsuario',
        type: 'POST',
        data: { numero_documento: numeroDocumento },
        success: function(response) {
            var usuario = JSON.parse(response);

            if (usuario.error) {
                alert('Error: ' + usuario.error);
            } else {
                // Llenar los campos del formulario con los datos obtenidos
                $('#correo').val(usuario.correo);
                $('#nombre_completo').val(usuario.nombre_completo);
                $('#numero_documento').val(usuario.numero_documento);
                $('#numero_celular').val(usuario.numero_celular);
                $('#rol').val(usuario.rol);
                
                // Campo oculto para indicar edición
                $('#numero_documento_original').val(usuario.numero_documento);

                // Mostrar el modal de edición
                $('#modalUsuario').modal('show');
            }
        },
        error: function() {
            alert('Error al obtener los datos del usuario.');
        }
    });
}

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

</body>
</html>
