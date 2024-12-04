<?php
class UsuariosController {


    // Función de registro (ya existente)
    public function registro() {
        require_once __DIR__ . '/../../config/database.php';

        $torres = $this->obtenerTorres($conn);
        $apartamentos = $this->obtenerApartamentos($conn);
        $mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';

        require_once __DIR__ . '/../views/usuarios/registro.php';
    }

    
    public function registrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once __DIR__ . '/../../config/database.php';
    
            $correo = $_POST['correo'];
            $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            $nombre_completo = $_POST['nombre_completo'];
            $numero_documento = $_POST['numero_documento'];
            $numero_celular = $_POST['numero_celular'];
            $torre = $_POST['torre'];
            $apartamento = $_POST['apartamento'];
    
            // Verificar si el correo o número de documento ya existen
            $stmt = $conn->prepare("SELECT * FROM tb_usuarios WHERE correo = ? OR numero_documento = ?");
            $stmt->bind_param("ss", $correo, $numero_documento);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                // Si el correo o el número de documento ya están registrados
                header("Location: /PROYECTO_APCR3.0/usuarios/registro?mensaje=El correo o número de documento ya está registrado.");
                exit();
            } else {
                // Insertar los datos en la base de datos
                $stmt = $conn->prepare("INSERT INTO tb_usuarios (correo, clave, nombre_completo, numero_documento, numero_celular, id_torre, id_apartamento) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssi", $correo, $clave, $nombre_completo, $numero_documento, $numero_celular, $torre, $apartamento);
    
                if ($stmt->execute()) {
                    // Si el registro es exitoso, redirigir al login
                    header("Location: /PROYECTO_APCR3.0/usuarios/login?mensaje=Registro%20exitoso,%20por%20favor%20inicia%20sesión.");
                    exit();
                } else {
                    // Si hubo un error al registrar
                    header("Location: /PROYECTO_APCR3.0/usuarios/registro?mensaje=Error al registrar el usuario.");
                    exit();
                }
            }
        }
    }
    

    // Función de login (nueva)
    public function login() {
        require_once __DIR__ . '/../views/usuarios/login.php';
    }

    // Función para validar login (nueva)
    public function validarLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once __DIR__ . '/../../config/database.php';
    
            $correo = $_POST['correo'];
            $clave = $_POST['clave'];
    
            // Consulta SQL con JOIN para obtener torre y apartamento
            $query = "
                SELECT u.*, t.nombre_torre, a.numero_apartamento 
                FROM tb_usuarios u
                LEFT JOIN tb_torres t ON u.id_torre = t.id
                LEFT JOIN tb_apartamentos a ON u.id_apartamento = a.id
                WHERE u.correo = ?
            ";
    
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
    
                // Verificar la contraseña
                if (password_verify($clave, $usuario['clave'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    // Configurar la sesión con los datos del usuario
                    $_SESSION['usuario'] = $usuario['nombre_completo'];
                    $_SESSION['rol'] = $usuario['rol'];  
                    $_SESSION['torre'] = $usuario['nombre_torre'] ?? 'N/A';
                    $_SESSION['apartamento'] = $usuario['numero_apartamento'] ?? 'N/A';
    
                    header("Location: /PROYECTO_APCR3.0/usuarios/principal");
                    exit();
                } else {
                    header("Location: /PROYECTO_APCR3.0/usuarios/login?mensaje=Contraseña incorrecta");
                    exit();
                }
            } else {
                header("Location: /PROYECTO_APCR3.0/usuarios/login?mensaje=Correo no registrado");
                exit();
            }
        }
    }
    

    // Función para la página principal del usuario (nueva)
    public function principal() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            header("Location: /PROYECTO_APCR3.0/usuarios/login");
            exit();
        }

        $usuario = $_SESSION['usuario'];
        require_once __DIR__ . '/../views/usuarios/principal.php';
    }

    // Función para obtener las torres (ya existente)
    private function obtenerTorres($conn) {
        $query = "SELECT * FROM tb_torres";
        $result = $conn->query($query);
        $torres = [];

        while ($row = $result->fetch_assoc()) {
            $torres[] = $row;
        }
        return $torres;
    }

    // Función para obtener los apartamentos (ya existente)
    private function obtenerApartamentos($conn) {
        $query = "SELECT * FROM tb_apartamentos";
        $result = $conn->query($query);
        $apartamentos = [];

        while ($row = $result->fetch_assoc()) {
            $apartamentos[] = $row;
        }
        return $apartamentos;
    }

    public function mostrarUsuarios() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] !== 'administrador' && $_SESSION['rol'] !== 'porteria')) {
            // Redirigir a la página principal si no tiene permisos
            header("Location: /PROYECTO_APCR3.0/usuarios/principal?mensaje=No%20tienes%20permiso%20para%20acceder%20a%20esta%20sección.");
            exit();
        }
    
        require_once __DIR__ . '/../../config/database.php';
    
        // Obtener todos los usuarios
        $queryUsuarios = "SELECT correo, numero_documento, nombre_completo, rol FROM tb_usuarios";
        $resultUsuarios = $conn->query($queryUsuarios);
        $usuarios = [];
        while ($row = $resultUsuarios->fetch_assoc()) {
            $usuarios[] = $row;
        }
    
        // Pasar los datos de los usuarios a la vista
        require_once __DIR__ . '/../views/usuarios/crear_usuario.php';
    }
    

    public function crearUsuario() {
        require_once __DIR__ . '/../../config/database.php';
    
        // Obtener todas las torres
        $queryTorres = "SELECT id, nombre_torre FROM tb_torres";
        $resultTorres = $conn->query($queryTorres);
        $torres = [];
        while ($row = $resultTorres->fetch_assoc()) {
            $torres[] = $row;
        }
    
        // Obtener todos los apartamentos
        $queryApartamentos = "SELECT id, numero_apartamento FROM tb_apartamentos";
        $resultApartamentos = $conn->query($queryApartamentos);
        $apartamentos = [];
        while ($row = $resultApartamentos->fetch_assoc()) {
            $apartamentos[] = $row;
        }
    
        // Pasar las variables a la vista
        require_once __DIR__ . '/../views/usuarios/crear_usuario.php';
    }
    
    
    public function guardarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once __DIR__ . '/../../config/database.php';
    
            $correo = $_POST['correo'];
            if (empty($_POST['clave']) && empty($numero_documento_original)) {
                // Si no hay contraseña y es un usuario nuevo, mostrar error
                header("Location: /PROYECTO_APCR3.0/usuarios/crear?mensaje=La contraseña es obligatoria para un usuario nuevo.");
                exit();
            }
            
            // Encriptar la contraseña solo si está definida
            $clave = isset($_POST['clave']) && !empty($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : null;
                        $nombre_completo = $_POST['nombre_completo'];
            $numero_documento = $_POST['numero_documento'];
            $numero_celular = $_POST['numero_celular'];
            $rol = $_POST['rol'];
            $numero_documento_original = $_POST['numero_documento_original']; // Campo oculto para verificar si es edición
    
            $torre = null;
            $apartamento = null;
    
            // Si es residente, obtener torre y apartamento
            if ($rol === 'residente') {
                $torre = $_POST['torre'];
                $apartamento = $_POST['apartamento'];
            }
    
            // ** Crear un nuevo usuario **
            if (empty($numero_documento_original)) {
                // Verificar si el correo o número de documento ya existen en otro usuario
                $stmt = $conn->prepare("SELECT * FROM tb_usuarios WHERE correo = ? OR numero_documento = ?");
                $stmt->bind_param("ss", $correo, $numero_documento);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    header("Location: /PROYECTO_APCR3.0/usuarios/crear?mensaje=El correo o número de documento ya está registrado.");
                    exit();
                }
    
                // Crear el usuario si no hay conflictos
                $stmt = $conn->prepare("INSERT INTO tb_usuarios (correo, clave, nombre_completo, numero_documento, numero_celular, id_torre, id_apartamento, rol) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssis", $correo, $clave, $nombre_completo, $numero_documento, $numero_celular, $torre, $apartamento, $rol);
                if ($stmt->execute()) {
                    header("Location: /PROYECTO_APCR3.0/usuarios/mostrar?mensaje=Usuario creado exitosamente");
                    exit();
                } else {
                    header("Location: /PROYECTO_APCR3.0/usuarios/crear?mensaje=Error al crear el usuario.");
                    exit();
                }
            } else {
                // ** Editar un usuario existente **
                // Si el correo ha cambiado, verificar si ya existe otro usuario con ese correo
                if ($correo !== $_POST['correo_original']) {
                    $stmt = $conn->prepare("SELECT * FROM tb_usuarios WHERE correo = ?");
                    $stmt->bind_param("s", $correo);
                    $stmt->execute();
                    $result = $stmt->get_result();
    
                    if ($result->num_rows > 0) {
                        header("Location: /PROYECTO_APCR3.0/usuarios/crear?mensaje=El correo ya está registrado por otro usuario.");
                        exit();
                    }
                }
    
                // Actualizar el usuario
                $query = "UPDATE tb_usuarios SET correo = ?, nombre_completo = ?, numero_documento = ?, numero_celular = ?, rol = ?, id_torre = ?, id_apartamento = ?";
                if (!empty($clave)) {
                    $query .= ", clave = ?"; // Solo actualiza la contraseña si se proporciona
                }
                $query .= " WHERE numero_documento = ?";
    
                $stmt = $conn->prepare($query);
                if (!empty($clave)) {
                    $stmt->bind_param("sssssssis", $correo, $nombre_completo, $numero_documento, $numero_celular, $rol, $torre, $apartamento, $clave, $numero_documento_original);
                } else {
                    $stmt->bind_param("ssssssis", $correo, $nombre_completo, $numero_documento, $numero_celular, $rol, $torre, $apartamento, $numero_documento_original);
                }
    
                if ($stmt->execute()) {
                    header("Location: /PROYECTO_APCR3.0/usuarios/mostrar?mensaje=Usuario actualizado exitosamente");
                    exit();
                } else {
                    header("Location: /PROYECTO_APCR3.0/usuarios/crear?mensaje=Error al actualizar el usuario.");
                    exit();
                }
            }
        }
    }

    public function eliminarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../config/database.php';
    
            $numero_documento = $_POST['id'];
    
            // Consulta para eliminar al usuario
            $stmt = $conn->prepare("DELETE FROM tb_usuarios WHERE numero_documento = ?");
            $stmt->bind_param("s", $numero_documento);
    
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
            }
        }
    }
    
    
    public function obtenerUsuario() {
        require_once __DIR__ . '/../../config/database.php';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_documento = $_POST['numero_documento'];
    
            if (!$numero_documento) {
                echo json_encode(['error' => 'Número de documento no proporcionado']);
                exit;
            }
    
            $stmt = $conn->prepare("SELECT correo, nombre_completo, numero_documento, numero_celular, rol, id_torre, id_apartamento FROM tb_usuarios WHERE numero_documento = ?");
            $stmt->bind_param("s", $numero_documento);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                echo json_encode($usuario);
            } else {
                echo json_encode(['error' => 'Usuario no encontrado.']);
            }
        } else {
            echo json_encode(['error' => 'Método no permitido.']);
        }
    }
    

    
}

?>
