<?php
class Activity {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProjectsByUser($id_usuario) {
        $query = "SELECT p.id_proyecto, p.titulo_proyecto, m.nombre_marca 
                  FROM proyectos p
                  JOIN proyecto_usuarios pu ON p.id_proyecto = pu.id_proyecto
                  JOIN marcas m ON p.id_marca = m.id_marca
                  WHERE pu.id_usuario = :id_usuario AND p.id_estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar reporte diario recibiendo el porcentaje de avance
    public function createActivityWithEvidence($id_proyecto, $id_colaborador, $titulo, $descripcion, $porcentaje_avance, $archivo) {
        try {
            $this->conn->beginTransaction();

            $queryAct = "INSERT INTO actividades (id_proyecto, id_colaborador, titulo, descripcion, fecha_limite, porcentaje_avance, estado_actividad) 
                         VALUES (:id_proyecto, :id_colaborador, :titulo, :descripcion, CURDATE(), :porcentaje_avance, :estado_actividad)";
            
            // Determinamos el estado según el porcentaje
            $estado_actividad = ($porcentaje_avance >= 100) ? 'Completado' : 'En Proceso';

            $stmtAct = $this->conn->prepare($queryAct);
            $stmtAct->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
            $stmtAct->bindParam(':id_colaborador', $id_colaborador, PDO::PARAM_INT);
            $stmtAct->bindParam(':titulo', $titulo);
            $stmtAct->bindParam(':descripcion', $descripcion);
            $stmtAct->bindParam(':porcentaje_avance', $porcentaje_avance);
            $stmtAct->bindParam(':estado_actividad', $estado_actividad);
            $stmtAct->execute();

            $id_actividad = $this->conn->lastInsertId();

            if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                $nombreArchivo = 'evidencia_' . $id_actividad . '_' . time() . '.' . $ext;
                $directorioDestino = 'uploads/evidencias/';

                if (!is_dir($directorioDestino)) {
                    mkdir($directorioDestino, 0777, true);
                }

                $rutaFinal = $directorioDestino . $nombreArchivo;

                if (move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
                    $queryEvi = "INSERT INTO evidencias (id_actividad, ruta_archivo, tipo_archivo, ruta_subida) 
                                 VALUES (:id_actividad, :ruta_archivo, :tipo_archivo, NOW())";
                    $stmtEvi = $this->conn->prepare($queryEvi);
                    $stmtEvi->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);
                    $stmtEvi->bindParam(':ruta_archivo', $rutaFinal);
                    $tipo = $archivo['type'];
                    $stmtEvi->bindParam(':tipo_archivo', $tipo);
                    $stmtEvi->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getActivitiesByUser($id_usuario) {
        $query = "SELECT a.*, p.titulo_proyecto, m.nombre_marca, e.ruta_archivo 
                  FROM actividades a
                  JOIN proyectos p ON a.id_proyecto = p.id_proyecto
                  JOIN marcas m ON p.id_marca = m.id_marca
                  LEFT JOIN evidencias e ON a.id_actividad = e.id_actividad
                  WHERE a.id_colaborador = :id_usuario
                  ORDER BY a.id_actividad ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reports as &$rep) {
            $rep['observaciones'] = $this->getObservationsByActivity($rep['id_actividad']);
        }
        unset($rep);

        return $reports;
    }

    public function getObservationsByActivity($id_actividad) {
        $query = "SELECT o.*, u.nombre as autor_observacion 
                  FROM observaciones o
                  JOIN usuarios u ON o.id_usuario = u.id_usuario
                  WHERE o.id_actividad = :id_actividad
                  ORDER BY o.id_observacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>