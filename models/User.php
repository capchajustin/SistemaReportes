<?php
class User {
    private $conn;
    private $table_name = "usuarios"; // O el nombre de tu tabla en la BD

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los empleados con su rol
    public function getAllUsers() {
        $query = "SELECT u.id_usuario, u.nombre, u.email, u.id_rol, r.nombre_rol 
                  FROM " . $this->table_name . " u
                  LEFT JOIN roles r ON u.id_rol = r.id_rol
                  ORDER BY u.id_usuario ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo empleado
    public function createUser($nombre, $email, $password, $id_rol) {
        $query = "INSERT INTO " . $this->table_name . " (nombre, email, password_hash, id_rol) 
                  VALUES (:nombre, :email, :password_hash, :id_rol)";
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':id_rol', $id_rol);

        return $stmt->execute();
    }

    // Actualizar rol y/o contraseña de un empleado existente por el Administrador
    public function updateUserByAdmin($id_usuario, $id_rol, $password = null) {
        if (!empty($password)) {
            $query = "UPDATE " . $this->table_name . " SET id_rol = :id_rol, password_hash = :password_hash WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password_hash', $password_hash);
        } else {
            $query = "UPDATE " . $this->table_name . " SET id_rol = :id_rol WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->bindParam(':id_rol', $id_rol);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }

    // Actualizar perfil de usuario propio (Nombre y Correo)
    public function updateProfile($id_usuario, $nombre, $email) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, email = :email 
                  WHERE id_usuario = :id_usuario";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_usuario', $id_usuario);

        return $stmt->execute();
    }

    // Buscar usuario por login/email para autenticación
    public function emailByLogin($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>