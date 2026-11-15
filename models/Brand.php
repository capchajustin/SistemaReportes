<?php
class Brand {
    private $conn;
    private $table_name = "marcas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBrands() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_marca ASC"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear marca, proyecto inicial con responsable y asignación de trabajadores
    public function createBrandWithProjectAndWorkers($nombre_marca, $cliente_asociado, $estado, $id_responsable, $trabajadores = []) {
        try {
            $this->conn->beginTransaction();

            $queryBrand = "INSERT INTO " . $this->table_name . " (nombre_marca, cliente_asociado, estado) 
                           VALUES (:nombre_marca, :cliente_asociado, :estado)";
            $stmt = $this->conn->prepare($queryBrand);
            $stmt->bindParam(':nombre_marca', $nombre_marca);
            $stmt->bindParam(':cliente_asociado', $cliente_asociado);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->execute();

            $id_marca = $this->conn->lastInsertId();

            $titulo_proyecto_generado = "Proyecto " . $nombre_marca;

            $queryProj = "INSERT INTO proyectos (id_marca, id_responsable, id_estado, titulo_proyecto, descripcion, fecha_inicio, fecha_fin_estimada) 
              VALUES (:id_marca, :id_responsable, 1, :titulo_proyecto, :descripcion, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))";
             
            $stmtProj = $this->conn->prepare($queryProj);
            $stmtProj->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmtProj->bindParam(':id_responsable', $id_responsable, PDO::PARAM_INT);
            $stmtProj->bindParam(':titulo_proyecto', $titulo_proyecto_generado); // Usamos la variable formateada
            $descripcion_def = "Proyecto operativo asignado para la marca " . $nombre_marca;
            $stmtProj->bindParam(':descripcion', $descripcion_def);
            $stmtProj->execute();

            $id_proyecto = $this->conn->lastInsertId();

            // Insertar trabajadores seleccionados inicialmente
            if (!empty($trabajadores)) {
                foreach ($trabajadores as $id_usuario) {
                    $stmtUser = $this->conn->prepare("INSERT IGNORE INTO proyecto_usuarios (id_proyecto, id_usuario) VALUES (:id_proyecto, :id_usuario)");
                    $stmtUser->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
                    $stmtUser->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmtUser->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function updateBrandAndProject($id_marca, $nombre_marca, $cliente_asociado, $estado, $id_responsable) {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET nombre_marca = :nombre_marca, cliente_asociado = :cliente_asociado, estado = :estado WHERE id_marca = :id_marca");
            $stmt->bindParam(':nombre_marca', $nombre_marca);
            $stmt->bindParam(':cliente_asociado', $cliente_asociado);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->execute();

            $titulo_proyecto_generado = "Proyecto " . $nombre_marca;

            $stmtProj = $this->conn->prepare("UPDATE proyectos SET titulo_proyecto = :titulo_proyecto, id_responsable = :id_responsable WHERE id_marca = :id_marca");
            $stmtProj->bindParam(':titulo_proyecto', $titulo_proyecto_generado);
            $stmtProj->bindParam(':id_responsable', $id_responsable, PDO::PARAM_INT);
            $stmtProj->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmtProj->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getProjectsByBrand($id_marca) {
        $query = "SELECT p.id_proyecto, p.titulo_proyecto, p.fecha_inicio, p.id_responsable, u.nombre as responsable 
                  FROM proyectos p
                  LEFT JOIN usuarios u ON p.id_responsable = u.id_usuario
                  WHERE p.id_marca = :id_marca 
                  ORDER BY p.id_proyecto ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Adjuntar los trabajadores asignados a cada proyecto
        foreach ($projects as &$proj) {
            $proj['trabajadores'] = $this->getWorkersByProject($proj['id_proyecto']);
        }
        unset($proj);

        return $projects;
    }

    public function getWorkersByProject($id_proyecto) {
        $query = "SELECT u.id_usuario, u.nombre 
                  FROM proyecto_usuarios pu
                  JOIN usuarios u ON pu.id_usuario = u.id_usuario
                  WHERE pu.id_proyecto = :id_proyecto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignWorkersToProject($id_proyecto, $trabajadores) {
        try {
            $this->conn->beginTransaction();
            foreach ($trabajadores as $id_usuario) {
                $stmt = $this->conn->prepare("INSERT IGNORE INTO proyecto_usuarios (id_proyecto, id_usuario) VALUES (:id_proyecto, :id_usuario)");
                $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function removeWorkerFromProject($id_proyecto, $id_usuario) {
        $query = "DELETE FROM proyecto_usuarios WHERE id_proyecto = :id_proyecto AND id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>