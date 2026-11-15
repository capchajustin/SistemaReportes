<?php
class Project {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

         public function getAllBrands() {
        $query = "SELECT * FROM marcas ORDER BY id_marca ASC"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectByBrand($id_marca) {
        $query = "SELECT p.*, u.nombre as responsable, m.nombre_marca 
                  FROM proyectos p
                  JOIN marcas m ON p.id_marca = m.id_marca
                  LEFT JOIN usuarios u ON p.id_responsable = u.id_usuario
                  WHERE p.id_marca = :id_marca LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   public function getReportsByProjectAndDate($id_proyecto, $fecha, $fecha_fin = null) {
        if (!empty($fecha_fin)) {
            // Consulta cuando se usa un rango de fechas (Desde - Hasta)
            $query = "SELECT a.*, u.nombre as colaborador, e.ruta_archivo 
                      FROM actividades a
                      JOIN usuarios u ON a.id_colaborador = u.id_usuario
                      LEFT JOIN evidencias e ON a.id_actividad = e.id_actividad
                      WHERE a.id_proyecto = :id_proyecto 
                        AND DATE(a.fecha_limite) BETWEEN :fecha_inicio AND :fecha_fin
                      ORDER BY a.fecha_limite ASC, a.id_actividad ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $fecha);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
        } else {
            // Consulta para una fecha específica
            $query = "SELECT a.*, u.nombre as colaborador, e.ruta_archivo 
                      FROM actividades a
                      JOIN usuarios u ON a.id_colaborador = u.id_usuario
                      LEFT JOIN evidencias e ON a.id_actividad = e.id_actividad
                      WHERE a.id_proyecto = :id_proyecto AND DATE(a.fecha_limite) = :fecha
                      ORDER BY a.id_actividad ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
        }

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

    // Actualizar estado del reporte (Aceptado, Observado, etc.)
    public function updateReportStatus($id_actividad, $estado) {
        $query = "UPDATE actividades SET estado_actividad = :estado WHERE id_actividad = :id_actividad";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Agregar observación y marcar estado como Observado
    public function addObservation($id_actividad, $id_usuario, $comentario) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO observaciones (id_actividad, id_usuario, comentario, estado_correccion, fecha_creacion) 
                      VALUES (:id_actividad, :id_usuario, :comentario, 'Pendiente', NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':comentario', $comentario);
            $stmt->execute();

            $this->updateReportStatus($id_actividad, 'Observado');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Limpiar / eliminar observaciones al corregirse y aceptar el reporte
    public function resolveObservationsAndAccept($id_actividad) {
        try {
            $this->conn->beginTransaction();

            $query = "DELETE FROM observaciones WHERE id_actividad = :id_actividad";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);
            $stmt->execute();

            $this->updateReportStatus($id_actividad, 'Aceptado');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>