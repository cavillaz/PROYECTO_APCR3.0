<?php
class ParqueaderosController {

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
    
        $parqueadero_id = $_POST['parqueadero_id'];
        $nombre_persona = $_POST['nombre_persona'];
        $documento_persona = $_POST['documento_persona'];
        $tipo_vehiculo = $_POST['tipo_vehiculo'];
        $placa_vehiculo = $_POST['placa_vehiculo'];
        $tipo_parqueadero = $_POST['tipo_parqueadero'];
    
        // Verificar si el parqueadero ya está reservado
        $checkQuery = "SELECT * FROM tb_historial_parqueaderos 
                       WHERE (documento_persona = ? OR placa_vehiculo = ?) 
                       AND estado IN ('pendiente_aprobacion', 'ocupado')";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $documento_persona, $placa_vehiculo);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    
        if ($checkResult->num_rows > 0) {
            header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=Error: Ya tienes un parqueadero reservado o pendiente de aprobación&tipo=error");
            exit();
        }
    
        $query = "INSERT INTO tb_historial_parqueaderos 
                  (parqueadero_id, nombre_persona, documento_persona, tipo_vehiculo, placa_vehiculo, tipo_parqueadero, fecha_solicitud, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pendiente_aprobacion')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $parqueadero_id, $nombre_persona, $documento_persona, $tipo_vehiculo, $placa_vehiculo, $tipo_parqueadero);
    
        if ($stmt->execute()) {
            $updateQuery = "UPDATE tb_parqueaderos SET estado = 'pendiente_aprobacion' WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $parqueadero_id);
            $updateStmt->execute();
    
            header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=Parqueadero reservado correctamente&tipo=success");
        } else {
            header("Location: /PROYECTO_APCR3.0/parqueaderos/gestion?mensaje=Error al reservar el parqueadero&tipo=error");
        }
    }
    

    
    
    public function obtenerDatosParqueadero($parqueadero_id) {
        // Conexión a la base de datos
        require_once __DIR__ . '/../../config/database.php';
    
        // Consulta para obtener los datos del parqueadero
        $query = "SELECT h.nombre_persona, h.documento_persona, h.tipo_vehiculo, h.placa_vehiculo, 
                         h.tipo_parqueadero, h.pago, h.estado
                  FROM tb_historial_parqueaderos h
                  WHERE h.parqueadero_id = ? AND h.estado IN ('pendiente_aprobacion', 'ocupado')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $parqueadero_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Retornar los datos si existe un parqueadero pendiente o ocupado
        } else {
            return null;  // Retornar null si el parqueadero está libre
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
