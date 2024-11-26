<?php
// index.php

// Incluir los controladores necesarios
require_once 'app/controllers/UsuariosController.php';
require_once 'app/controllers/ParqueaderosController.php';
require_once 'app/controllers/ZonasComunesController.php';

// Iniciar sesión
session_start();

// Obtener la URL solicitada
$request = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

// Eliminar parámetros adicionales de la URL si existen (por ejemplo, ?id=1)
$request = strtok($request, '?');

// Verificar si el usuario accede a la raíz del proyecto
if ($request === '/PROYECTO_APCR3.0/' || $request === '/') {
    header("Location: /PROYECTO_APCR3.0/usuarios/login");
    exit();
}

// Definir rutas protegidas
$rutasProtegidas = [
    '/PROYECTO_APCR3.0/usuarios/principal',
    '/PROYECTO_APCR3.0/usuarios/mostrar',
    '/PROYECTO_APCR3.0/parqueaderos',
    '/PROYECTO_APCR3.0/parqueaderos/gestion',
    '/PROYECTO_APCR3.0/zonas_comunes',
    '/PROYECTO_APCR3.0/zonas_comunes/aprobacion',
    '/PROYECTO_APCR3.0/zonas_comunes/historial'
];

// Redirigir si no hay sesión activa para rutas protegidas
if (in_array($request, $rutasProtegidas) && !isset($_SESSION['usuario'])) {
    if ($request !== '/PROYECTO_APCR3.0/usuarios/login') { // Evita redirigir desde el login
        header("Location: /PROYECTO_APCR3.0/usuarios/login?mensaje=Por%20favor%20inicia%20sesión.");
        exit();
    }
}

// Resto del código de enrutamiento
switch (true) {
    // Rutas del módulo de usuarios
    case str_starts_with($request, '/PROYECTO_APCR3.0/usuarios'):
        $controller = new UsuariosController();

        switch ($request) {
            case '/PROYECTO_APCR3.0/usuarios/registro':
                $controller->registro();
                break;

            case '/PROYECTO_APCR3.0/usuarios/registrar':
                $controller->registrarUsuario();
                break;

            case '/PROYECTO_APCR3.0/usuarios/login':
                $controller->login();
                break;

            case '/PROYECTO_APCR3.0/usuarios/validarLogin':
                $controller->validarLogin();
                break;

            case '/PROYECTO_APCR3.0/usuarios/principal':
                $controller->principal();
                break;

            case '/PROYECTO_APCR3.0/usuarios/mostrar':
                $controller->mostrarUsuarios();
                break;

            case '/PROYECTO_APCR3.0/usuarios/crear':
                $controller->crearUsuario();
                break;

            case '/PROYECTO_APCR3.0/usuarios/guardar':
                $controller->guardarUsuario();
                break;

            case '/PROYECTO_APCR3.0/usuarios/eliminar':
                $controller->eliminarUsuario();
                break;

            case '/PROYECTO_APCR3.0/usuarios/logout':
                session_destroy();
                header("Location: /PROYECTO_APCR3.0/usuarios/login");
                break;

            case '/PROYECTO_APCR3.0/usuarios/obtenerUsuario':
                $controller->obtenerUsuario();
                break;

            default:
                http_response_code(404);
                echo 'Página no encontrada';
                break;
        }
        break;

    // Rutas del módulo de parqueaderos
    case str_starts_with($request, '/PROYECTO_APCR3.0/parqueaderos'):
        $controller = new ParqueaderosController();

        switch ($request) {
            case '/PROYECTO_APCR3.0/parqueaderos':
                $controller->index();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/gestion':
                $controller->gestion();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/solicitar':
                $controller->solicitar();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/aprobacion':
                $controller->aprobacion();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/aprobar':
                $controller->aprobar();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/rechazar':
                $controller->rechazar();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/liberar':
                $controller->liberar();
                break;

            case '/PROYECTO_APCR3.0/parqueaderos/historial':
                $controller->historial();
                break;

            default:
                http_response_code(404);
                echo 'Página no encontrada';
                break;
        }
        break;

    // Rutas del módulo de zonas comunes
    case str_starts_with($request, '/PROYECTO_APCR3.0/zonas_comunes'):
        $controller = new ZonasComunesController();

        switch ($request) {
            case '/PROYECTO_APCR3.0/zonas_comunes':
                $controller->index();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/solicitud':
                $controller->solicitud();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/aprobacion':
                $controller->aprobacion();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/historial':
                $controller->historial();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/disponibilidad':
                $controller->disponibilidad();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/registrar':
                $controller->registrarSolicitud();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/aprobar':
                $controller->aprobar();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/rechazar':
                $controller->rechazar();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/realizarSolicitud':
                $controller->realizarSolicitud();
                break;

            case '/PROYECTO_APCR3.0/zonas_comunes/obtenerEstadoZonas':
                $controller->obtenerEstadoZonas();
                break;

            default:
                http_response_code(404);
                echo 'Página no encontrada';
                break;
        }
        break;

    // Ruta por defecto (404)
    default:
        http_response_code(404);
        echo 'Página no encontrada';
        break;
}
