<?php
class ZonasComunesController {

    public function index() {
        require_once __DIR__ . '/../views/zonas_comunes/zonas_comunes.php';
    }
    

    // Cargar la vista de solicitud de zonas comunes
    public function solicitud() {
        require_once __DIR__ . '/../views/zonas_comunes/solicitud.php';
    }

    // Cargar la vista de aprobación de zonas comunes
    public function aprobacion() {
        require_once __DIR__ . '/../../config/database.php'; // Asegúrate de incluir la conexión
    
        global $conn; // Declara la conexión como global
    
        $query = "SELECT * FROM tb_zonas_comunes WHERE estado = 'pendiente_aprobacion'";
        $result = $conn->query($query);
    
        $solicitudes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
        }
    
        require_once __DIR__ . '/../views/zonas_comunes/aprobacion.php';
    }
    

    // Cargar el historial de zonas comunes desde la base de datos
    public function historial() {
        require_once __DIR__ . '/../../config/database.php';
    
        global $conn; // Asegurarse de usar la conexión global
    
        $query = "SELECT * FROM tb_zonas_comunes WHERE estado = 'ocupado'";
        $result = $conn->query($query);
    
        $zonasOcupadas = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $zonasOcupadas[] = $row;
            }
        }
    
        // Cargar la vista historial.php y pasar los datos de las zonas ocupadas
        require_once __DIR__ . '/../views/zonas_comunes/historial.php';
    }
    

    // Verificar la disponibilidad de una zona específica en una fecha dada
    public function disponibilidad() {
        require_once __DIR__ . '/../../config/database.php';
    
        global $conn;
    
        $zona = $_POST['zona'];
        $fecha = $_POST['fecha'];
    
        $query = "SELECT estado FROM tb_zonas_comunes 
                  WHERE nombre_zona = ? AND fecha_solicitada = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $zona, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $estado = $result->fetch_assoc()['estado'];
            echo json_encode(['estado' => $estado]);
        } else {
            echo json_encode(['estado' => 'libre']);
        }
    }
    

    // Registrar una nueva solicitud de zona común
    public function registrarSolicitud() {
        require_once __DIR__ . '/../../config/database.php';
        
        $zona = $_POST['nombre_zona'];
        $solicitante = $_POST['solicitante'];
        $fechaSolicitud = $_POST['fecha_solicitud'];
        
        $query = "INSERT INTO tb_zonas_comunes (nombre_zona, solicitante, fecha_solicitud, estado) 
                  VALUES (?, ?, ?, 'pendiente_aprobacion')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $zona, $solicitante, $fechaSolicitud);
        
        if ($stmt->execute()) {
            header("Location: /PROYECTO_APCR3.0/zonas_comunes/solicitud?mensaje=Solicitud registrada exitosamente&tipo=success");
        } else {
            header("Location: /PROYECTO_APCR3.0/zonas_comunes/solicitud?mensaje=Error al registrar la solicitud&tipo=error");
        }
    }

    // Aprobar una solicitud existente
    public function aprobar() {
        require_once __DIR__ . '/../../config/database.php';
    
        $id = $_POST['id'];
        $query = "UPDATE tb_zonas_comunes SET estado = 'ocupado' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo aprobar la solicitud.']);
        }
    }
    
    public function rechazar() {
        require_once __DIR__ . '/../../config/database.php';
    
        $id = $_POST['id'];
        $query = "UPDATE tb_zonas_comunes SET estado = 'rechazado' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo rechazar la solicitud.']);
        }
    }
    

    // Función para realizar una solicitud de zona común
    public function realizarSolicitud() {
        require_once __DIR__ . '/../../config/database.php';
    
        global $conn;
    
        $zona = $_POST['zona'];
        $fechaSolicitada = $_POST['fecha_solicitada'];
    
        // Verificar si la zona está ocupada
        $query = "SELECT estado FROM tb_zonas_comunes 
                  WHERE nombre_zona = ? AND fecha_solicitada = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $zona, $fechaSolicitada);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $estado = $result->fetch_assoc()['estado'];
            if ($estado === 'ocupado') {
                echo json_encode(['success' => false, 'message' => 'La zona ya está ocupada para esa fecha.']);
                return;
            }
        }
    
        // Continuar con la solicitud
        $query = "INSERT INTO tb_zonas_comunes (nombre_zona, solicitante, torre, apartamento, fecha_solicitud, fecha_solicitada, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, 'pendiente_aprobacion')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $_POST['zona'], $_POST['solicitante'], $_POST['torre'], $_POST['apartamento'], $_POST['fecha_solicitud'], $_POST['fecha_solicitada']);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Solicitud registrada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la solicitud.']);
        }
    }
    
    
    
    // Obtener el estado de las zonas para un mes y año específicos
    public function obtenerEstadoZonas() {
        require_once __DIR__ . '/../../config/database.php';
    
        $zona = $_POST['zona'];
        $mes = $_POST['mes'];
        $anio = $_POST['anio'];
    
        $query = "SELECT DAY(fecha_solicitada) AS dia, estado FROM tb_zonas_comunes 
                  WHERE nombre_zona = ? AND MONTH(fecha_solicitada) = ? AND YEAR(fecha_solicitada) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $zona, $mes, $anio);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $estados = [];
        while ($row = $result->fetch_assoc()) {
            $estados[$row['dia']] = $row['estado'];
        }
    
        echo json_encode($estados);
    }
    
    
}
?>
