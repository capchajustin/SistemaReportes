<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/Database.php';
require_once 'models/User.php';

class AuthController {

    public function index() {
        $this->login();
    }

    public function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($email) && !empty($password)) {
                $database = new Database();
                $db = $database->getConnection();
                
                $user = new User($db);
                $userData = $user->emailByLogin($email);

                if ($userData && password_verify($password, $userData['password_hash'])) {
                    $_SESSION['user_id'] = $userData['id_usuario'];
                    $_SESSION['user_name'] = $userData['nombre'];
                    $_SESSION['user_email'] = $userData['email'];
                    $_SESSION['role_id'] = $userData['id_rol'];
                    
                    if ($userData['id_rol'] == 1) {
                        $_SESSION['role_name'] = 'Administrador';
                    } elseif ($userData['id_rol'] == 2) {
                        $_SESSION['role_name'] = 'Responsable';
                    } else {
                        $_SESSION['role_name'] = 'Colaborador';
                    }

                    header("Location: index.php?controller=dashboard&action=index");
                    exit();
                } else {
                    $error = "Credenciales incorrectas o cuenta inactiva.";
                }
            } else {
                $error = "Por favor, complete todos los campos.";
            }
        }

        // Asegúrate de que tu vista de login esté en views/login.php o ajusta la ruta si está en views/auth/login.php
        require_once 'views/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
}
?>
