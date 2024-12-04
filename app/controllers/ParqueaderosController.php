<?php
class ParqueaderosController {

    public function index() {
        require_once __DIR__ . '/../views/parqueaderos/parqueaderos.php';
    }
    
    // Función para mostrar la página de gestión de parqueadero
    public function gestion() {
        require_once __DIR__ . '/../../config/database.php';
        
        // Obtener los parqueaderos desde la base de datos
        $query = "SELECT * FROM tb_parqueaderos";
        $result = $conn->query($query);
        $parqueaderos = $result->fetch_all(MYSQLI_ASSOC);

        // Cargar la vista de gestión de parqueadero
        require_once __DIR__ . '/../views/parqueaderos/gestion.php';
    }

    public function solicitar() {
        require_once __DIR__ . '/../../config/database.php';
    
        if (isset($_POST['parqueadero_id'], $_POST['nombre_persona'], $_POST['documento_persona'], 
                  $_POST['tipo_vehiculo'], $_POST['placa_vehiculo'], $_POST['tipo_parqueadero'])) {
    
            $parqueadero_id = (int) $_POST['parqueadero_id'];
            $nombre_persona = $_POST['nombre_persona'];
            $documento_persona = $_POST['documento_persona'];
            $tipo_vehiculo = $_POST['tipo_vehiculo'];
            $placa_vehiculo = $_POST['placa_vehiculo'];
            $tipo_parqueadero = $_POST['tipo_parqueadero'];
    
            // Registrar la fecha actual en formato adecuado
            $fecha_solicitud = date("Y-m-d H:i:s");
    
            // Verificar si el parqueadero ya está ocupado
            $queryEstado = "SELECT estado FROM tb_parqueaderos WHERE id = ?";
            $stmtEstado = $conn->prepare($queryEstado);
            $stmtEstado->bind_param('i', $parqueadero_id);
            $stmtEstado->execute();
            $resultEstado = $stmtEstado->get_result();
            $estado = $resultEstado->fetch_assoc()['estado'];
    
            if ($estado === 'ocupado') {
                header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=" . urlencode("El parqueadero ya está ocupado.") . "&tipo=error");
                exit;
            }
    
            // Validar que no exista la misma placa o documento en una solicitud activa
            $queryValidacion = "SELECT * FROM tb_historial_parqueaderos WHERE (placa_vehiculo = ? OR documento_persona = ?) AND estado IN ('pendiente_aprobacion', 'ocupado')";
            $stmtValidacion = $conn->prepare($queryValidacion);
            $stmtValidacion->bind_param('ss', $placa_vehiculo, $documento_persona);
            $stmtValidacion->execute();
            $resultValidacion = $stmtValidacion->get_result();
    
            if ($resultValidacion->num_rows > 0) {
                header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=" . urlencode("Ya existe una solicitud activa con la misma placa o documento.") . "&tipo=error");
                exit;
            }
    
            // Insertar en el historial de parqueaderos
            $query = "INSERT INTO tb_historial_parqueaderos (parqueadero_id, nombre_persona, documento_persona, tipo_vehiculo, placa_vehiculo, tipo_parqueadero, estado, fecha_solicitud) 
                      VALUES (?, ?, ?, ?, ?, ?, 'pendiente_aprobacion', ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('issssss', $parqueadero_id, $nombre_persona, $documento_persona, $tipo_vehiculo, $placa_vehiculo, $tipo_parqueadero, $fecha_solicitud);
    
            if ($stmt->execute()) {
                // Actualizar el estado del parqueadero
                $queryActualizar = "UPDATE tb_parqueaderos SET estado = 'pendiente_aprobacion' WHERE id = ?";
                $stmtActualizar = $conn->prepare($queryActualizar);
                $stmtActualizar->bind_param('i', $parqueadero_id);
                $stmtActualizar->execute();
    
                header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=" . urlencode("Solicitud registrada con éxito.") . "&tipo=success");
            } else {
                header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=" . urlencode("Error al registrar la solicitud.") . "&tipo=error");
            }
        } else {
            header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=" . urlencode("Faltan datos para procesar la solicitud.") . "&tipo=error");
        }
    }
    
    
        
    public function obtenerDatosParqueadero($parqueadero_id) {
        // Conexión a la base de datos
        require_once __DIR__ . '/../../config/database.php';
    
        // Consulta para verificar si el parqueadero está ocupado o pendiente de aprobación
        $queryHistorial = "SELECT h.nombre_persona, h.documento_persona, h.tipo_vehiculo, h.placa_vehiculo, 
                                  h.tipo_parqueadero, h.pago, h.estado
                           FROM tb_historial_parqueaderos h
                           WHERE h.parqueadero_id = ? AND h.estado IN ('pendiente_aprobacion', 'ocupado')";
        $stmtHistorial = $conn->prepare($queryHistorial);
        $stmtHistorial->bind_param('i', $parqueadero_id);
        $stmtHistorial->execute();
        $resultHistorial = $stmtHistorial->get_result();
    
        if ($resultHistorial->num_rows > 0) {
            return $resultHistorial->fetch_assoc();  // Retorna los datos si el parqueadero está ocupado o pendiente
        } else {
            // Si no hay registros en el historial, obtener los datos básicos del parqueadero
            $queryParqueadero = "SELECT id, numero_parqueadero, nombre_parqueadero, 'libre' AS estado 
                                 FROM tb_parqueaderos
                                 WHERE id = ?";
            $stmtParqueadero = $conn->prepare($queryParqueadero);
            $stmtParqueadero->bind_param('i', $parqueadero_id);
            $stmtParqueadero->execute();
            $resultParqueadero = $stmtParqueadero->get_result();
    
            if ($resultParqueadero->num_rows > 0) {
                return $resultParqueadero->fetch_assoc();  // Retorna los datos del parqueadero libre
            } else {
                return null;  // Si no existe el parqueadero
            }
        }
    }
    
    

    // Función para liberar un parqueadero (opcional, si quieres manejar la liberación)
    public function liberar() {
        require_once __DIR__ . '/../../config/database.php';
    
        $parqueadero_id = $_POST['parqueadero_id'];
        $valor_pagado = $_POST['valor_pagado'];
    
        $conn->begin_transaction();
    
        try {
            // Determinar si hubo pago: 1 si el valor_pagado > 0, 0 si es 0
            $pago = ($valor_pagado > 0) ? 1 : 0;
    
            // Actualizar el historial con el valor pagado y marcarlo como libre
            $query = "UPDATE tb_historial_parqueaderos 
                      SET estado = 'libre', valor_pagado = ?, pago = ?, fecha_liberacion = NOW() 
                      WHERE parqueadero_id = ? AND estado = 'ocupado'";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("dii", $valor_pagado, $pago, $parqueadero_id);
            $stmt->execute();
    
            // Actualizar el estado del parqueadero a 'libre'
            $updateQuery = "UPDATE tb_parqueaderos SET estado = 'libre' WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $parqueadero_id);
            $updateStmt->execute();
    
            $conn->commit();
            header("Location: /PROYECTO_APCR3.0/parqueaderos/aprobacion?mensaje=Parqueadero liberado&tipo=success");
        } catch (Exception $e) {
            $conn->rollback();
            header("Location: /PROYECTO_APCR3.0/parqueaderos/aprobacion?mensaje=Error al liberar parqueadero&tipo=error");
        }
    }
    


    public function aprobacion() {
        require_once __DIR__ . '/../../config/database.php';
    
        // Obtener los parqueaderos pendientes o aprobados
        $query = "SELECT h.*, p.numero_parqueadero 
                  FROM tb_historial_parqueaderos h
                  JOIN tb_parqueaderos p ON h.parqueadero_id = p.id 
                  WHERE h.estado IN ('pendiente_aprobacion', 'ocupado')";
        $result = $conn->query($query);
        $historial = $result->fetch_all(MYSQLI_ASSOC);
    
        require_once __DIR__ . '/../views/parqueaderos/aprobacion.php';
    }
    
    public function aprobar() {
        require_once __DIR__ . '/../../config/database.php';
    
        $id = $_GET['id'];
    
        // Actualizar estado a "ocupado" en tb_historial_parqueaderos
        $query = "UPDATE tb_historial_parqueaderos SET estado = 'ocupado', fecha_liberacion = NULL WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Cerrar el statement para evitar errores de sincronización
        $stmt->close();
    
        // Obtener el ID del parqueadero para actualizar en tb_parqueaderos
        $query2 = "SELECT parqueadero_id FROM tb_historial_parqueaderos WHERE id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->bind_result($parqueadero_id);
        $stmt2->fetch();
    
        // Cerrar el statement para evitar errores de sincronización
        $stmt2->close();
    
        // Actualizar estado a "ocupado" en tb_parqueaderos
        $updateQuery = "UPDATE tb_parqueaderos SET estado = 'ocupado' WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $parqueadero_id);
        $updateStmt->execute();
    
        // Cerrar el último statement
        $updateStmt->close();
    
        // Redirigir con un mensaje de éxito
        header("Location: /PROYECTO_APCR3.0/parqueaderos/aprobacion?mensaje=Aprobación completada&tipo=success");
    }
    
    
    public function rechazar() {
        // Conexión a la base de datos
        require_once __DIR__ . '/../../config/database.php';
    
        $id = $_GET['id'];
    
        // Iniciar una transacción
        $conn->begin_transaction();
    
        try {
            // Actualizar estado a "rechazado" en tb_historial_parqueaderos
            $query = "UPDATE tb_historial_parqueaderos SET estado = 'rechazado' WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();  // Asegúrate de cerrar el statement
    
            // Obtener el ID del parqueadero para actualizarlo en tb_parqueaderos
            $query2 = "SELECT parqueadero_id FROM tb_historial_parqueaderos WHERE id = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->bind_result($parqueadero_id);
            $stmt2->fetch();
            $stmt2->close();  // Cerrar el statement para evitar conflictos
    
            // Actualizar estado a "libre" en tb_parqueaderos
            $updateQuery = "UPDATE tb_parqueaderos SET estado = 'libre' WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $parqueadero_id);
            $updateStmt->execute();
            $updateStmt->close();  // Cerrar el statement
    
            // Confirmar la transacción si todo salió bien
            $conn->commit();
    
            // Redirigir con un mensaje de éxito
            header("Location: /PROYECTO_APCR3.0/parqueaderos/aprobacion?mensaje=Solicitud rechazada y parqueadero liberado&tipo=success");
        } catch (Exception $e) {
            // Revertir la transacción si hay algún error
            $conn->rollback();
            
            // Redirigir con un mensaje de error
            header("Location: /PROYECTO_APCR3.0/parqueaderos/aprobacion?mensaje=Error: " . $e->getMessage() . "&tipo=error");
        }
    }
    

    public function historial() {
        require_once __DIR__ . '/../../config/database.php';
    
        // Consulta para obtener todo el historial de parqueaderos
        $query = "SELECT * FROM tb_historial_parqueaderos";
        $result = $conn->query($query);
        $historial = $result->fetch_all(MYSQLI_ASSOC);
    
        // Cargar la vista del historial
        require_once __DIR__ . '/../views/parqueaderos/historial.php';
    }
    
    
}
?>
