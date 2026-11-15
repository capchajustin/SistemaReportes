<?php
class Database {
    // Credenciales extraídas directamente de FreeDB.tech
    private $host = "sql.freedb.tech";
    private $port = "3306";
    private $db_name = "freedb_5Qe6T2bL";
    private $username = "u_tvJEbF";
    private $password = "5T392BoQMHtk";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Permite sobreescribir con variables de entorno de Render si se configuran allá
        $host = getenv('DB_HOST') ?: $this->host;
        $port = getenv('DB_PORT') ?: $this->port;
        $db_name = getenv('DB_NAME') ?: $this->db_name;
        $username = getenv('DB_USER') ?: $this->username;
        $password = getenv('DB_PASS') ?: $this->password;

        try {
            $this->conn = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db_name, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión a la base de datos: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>