<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/Database.php';
require_once 'models/User.php';

class ProfileController {
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $mensaje = '';
        $error = '';

        // Procesar formulario de actualización
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nuevoNombre = trim($_POST['nombre']);
            $nuevoEmail = trim($_POST['email']);
            $userId = $_SESSION['user_id'];

            if (!empty($nuevoNombre) && !empty($nuevoEmail)) {
                if ($user->updateProfile($userId, $nuevoNombre, $nuevoEmail)) {
                    // Actualizar las variables de sesión para que el cambio se refleje al instante
                    $_SESSION['user_name'] = $nuevoNombre;
                    $mensaje = "Perfil actualizado correctamente en la base de datos.";
                } else {
                    $error = "Hubo un error al actualizar el perfil. Intente de nuevo.";
                }
            } else {
                $error = "Los campos no pueden estar vacíos.";
            }
        }

        require_once 'views/profile.php';
    }
}
?>
