<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/Database.php';
require_once 'models/User.php';

class EmployeesController {

    public function index() {
       // Verificar sesión y restringir estrictamente a Administradores (role_id == 1)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: index.php?controller=dashboard&action=index");
    exit();
}
    // Verificar si hay sesión activa y si es administrador (id_rol = 1)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            header("Location: index.php?controller=dashboard&action=index");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $mensaje = '';
        $error = '';

        // Procesar solicitudes POST (Crear o Actualizar)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $actionType = $_POST['action_type'] ?? '';

            if ($actionType === 'create') {
                $nombre = trim($_POST['nombre']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $id_rol = $_POST['id_rol'];

                if (!empty($nombre) && !empty($email) && !empty($password) && !empty($id_rol)) {
                    if ($user->createUser($nombre, $email, $password, $id_rol)) {
                        $mensaje = "Empleado creado exitosamente en la base de datos.";
                    } else {
                        $error = "Error al registrar el empleado. El correo podría estar duplicado.";
                    }
                } else {
                    $error = "Todos los campos son obligatorios para crear un empleado.";
                }
            } elseif ($actionType === 'update') {
                $id_usuario = $_POST['id_usuario'];
                $id_rol = $_POST['id_rol'];
                $password = trim($_POST['password'] ?? '');

                // Si la contraseña está vacía, enviamos null para no sobrescribirla
                $passwordParam = !empty($password) ? $password : null;

                if ($user->updateUserByAdmin($id_usuario, $id_rol, $passwordParam)) {
                    $mensaje = "Datos del empleado actualizados correctamente.";
                } else {
                    $error = "Error al actualizar los datos del empleado.";
                }
            }
        }

        $employees = $user->getAllUsers();
        require_once 'views/employees.php';
    }
}
?>
