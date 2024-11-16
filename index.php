<?php
// index.php

// Incluir los controladores necesarios
require_once 'app/controllers/UsuariosController.php';
require_once 'app/controllers/ParqueaderosController.php';
require_once 'app/controllers/ZonasComunesController.php';



// Obtener la URL solicitada
$request = $_SERVER['REQUEST_URI'];

// Eliminar parámetros adicionales de la URL si existen (por ejemplo, ?id=1)
$request = strtok($request, '?');

// Enrutamiento básico utilizando switch
switch ($request) {
    case '/PROYECTO_APCR3.0/usuarios/registro' :
        $controller = new UsuariosController();
        $controller->registro();
        break;

    case '/PROYECTO_APCR3.0/usuarios/registrar' :
        $controller = new UsuariosController();
        $controller->registrarUsuario();
        break;

    case '/PROYECTO_APCR3.0/usuarios/login':
        $controller = new UsuariosController();
        $controller->login();
        break;
    
    case '/PROYECTO_APCR3.0/usuarios/validarLogin':
        $controller = new UsuariosController();
        $controller->validarLogin();
        break;
    
    case '/PROYECTO_APCR3.0/usuarios/principal':
        $controller = new UsuariosController();
        $controller->principal();
        break;
    
    case '/PROYECTO_APCR3.0/usuarios/mostrar':
        $controller = new UsuariosController();
        $controller->mostrarUsuarios();
        break;

    case '/PROYECTO_APCR3.0/usuarios/crear':
        $controller = new UsuariosController();
        $controller->crearUsuario();
        break;

    case '/PROYECTO_APCR3.0/usuarios/guardar':
        $controller = new UsuariosController();
        $controller->guardarUsuario();
        break;

    case '/PROYECTO_APCR3.0/usuarios/logout':
        session_start();
        session_destroy();
        header("Location: /PROYECTO_APCR3.0/usuarios/login");
        break;

    case '/PROYECTO_APCR3.0/usuarios/eliminar':
        $controller = new UsuariosController();
        $controller->eliminarUsuario();
        break;
        
    case '/PROYECTO_APCR3.0/usuarios/obtenerUsuario':
        $controller = new UsuariosController();
        $controller->obtenerUsuario();
        break; 

    // Corregir rutas del módulo de parqueaderos
    case '/PROYECTO_APCR3.0/parqueaderos':
        require_once __DIR__ . '/app/views/parqueaderos/parqueaderos.php';
        break;

    case '/PROYECTO_APCR3.0/parqueaderos/gestion':
        $controller = new ParqueaderosController();
        $controller->gestion();
        break;
    
    case '/PROYECTO_APCR3.0/parqueaderos/solicitar':
        $controller = new ParqueaderosController();
        $controller->solicitar(); // Llama al método correcto
        break;
        
    case '/PROYECTO_APCR3.0/parqueaderos/obtenerDatosParqueadero':
        $controller = new ParqueaderosController();
        echo json_encode($controller->obtenerDatosParqueadero($_POST['parqueadero_id']));
        break;
        
    case '/PROYECTO_APCR3.0/parqueaderos/aprobacion':
        $controller = new ParqueaderosController();
        $controller->aprobacion();
        break;
    
    case '/PROYECTO_APCR3.0/parqueaderos/aprobar':
        $controller = new ParqueaderosController();
        $controller->aprobar();
        break;
    
    case '/PROYECTO_APCR3.0/parqueaderos/rechazar':
        $controller = new ParqueaderosController();
        $controller->rechazar();
        break;
    
    case '/PROYECTO_APCR3.0/parqueaderos/liberar':
        $controller = new ParqueaderosController();
        $controller->liberar();
        break;

    case '/PROYECTO_APCR3.0/parqueaderos/historial':
        $controller = new ParqueaderosController();
        $controller->historial();
        break;
    
    // Rutas del módulo de Zonas Comunes
    case '/PROYECTO_APCR3.0/zonas_comunes':
        require_once __DIR__ . '/app/views/zonas_comunes/zonas_comunes.php';
        break;

    case '/PROYECTO_APCR3.0/zonas_comunes/solicitud':
        $controller = new ZonasComunesController();
        $controller->solicitud();
        break;
        
    case '/PROYECTO_APCR3.0/zonas_comunes/aprobacion':
        $controller = new ZonasComunesController();
        $controller->aprobacion();
        break;
        
    case '/PROYECTO_APCR3.0/zonas_comunes/historial':
        $controller = new ZonasComunesController();
        $controller->historial();
        break;

    case '/PROYECTO_APCR3.0/zonas_comunes/disponibilidad':
        $controller = new ZonasComunesController();
        $controller->disponibilidad();
        break;
    
    case '/PROYECTO_APCR3.0/zonas_comunes/registrar':
        $controller = new ZonasComunesController();
        $controller->registrarSolicitud();
        break;
    
    case '/PROYECTO_APCR3.0/zonas_comunes/aprobar':
        $controller = new ZonasComunesController();
        $controller->aprobar();
        break;
    
    case '/PROYECTO_APCR3.0/zonas_comunes/rechazar':
        $controller = new ZonasComunesController();
        $controller->rechazar();

    case '/PROYECTO_APCR3.0/zonas_comunes/realizarSolicitud':
        $controller = new ZonasComunesController();
        $controller->realizarSolicitud();
        break;

    case '/PROYECTO_APCR3.0/zonas_comunes/obtenerEstadoZonas':
        $controller = new ZonasComunesController();
        $controller->obtenerEstadoZonas();
        break;

        
    default:
        http_response_code(404);
        echo 'Página no encontrada';
        break;
}
