<?php
require_once 'config/database.php';
require_once 'models/Brand.php';
require_once 'models/User.php';

class BrandsController {

    public function index() {
        // Verificar sesión y restringir estrictamente a Administradores (1) y Responsables (2)
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
            header("Location: index.php?controller=dashboard&action=index");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $brandModel = new Brand($db);
        $userModel = new User($db);

        $mensaje = '';
        $error = '';

        // Permitir peticiones POST si el usuario es Admin (1) o Responsable (2)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['role_id'])) {
            $action = $_POST['action_type'] ?? '';
            $role_id = intval($_SESSION['role_id']);

            if ($action === 'create_brand') {
                // SOLO ADMIN (1) PUEDE CREAR MARCAS NUEVAS
                if ($role_id != 1) {
                    header("Location: index.php?controller=brands&action=index");
                    exit();
                }

                $nombre_marca = trim($_POST['nombre_marca'] ?? '');
                $cliente_asociado = trim($_POST['cliente_asociado'] ?? '');
                $estado = intval($_POST['estado'] ?? 1);
                $id_responsable = intval($_POST['id_responsable'] ?? 0);
                $trabajadores = $_POST['trabajadores'] ?? [];

                if (!empty($nombre_marca) && !empty($cliente_asociado) && $id_responsable > 0) {
                    if ($brandModel->createBrandWithProjectAndWorkers($nombre_marca, $cliente_asociado, $estado, $id_responsable, $trabajadores)) {
                        $mensaje = "Marca y proyecto creados exitosamente.";
                    } else {
                        $error = "Error al registrar la marca y el proyecto.";
                    }
                } else {
                    $error = "Todos los campos obligatorios, incluyendo el responsable, son requeridos.";
                }

            } elseif ($action === 'update_brand') {
                // SOLO ADMIN (1) PUEDE ACTUALIZAR DATOS GENERALES DE LA MARCA
                if ($role_id != 1) {
                    header("Location: index.php?controller=brands&action=index");
                    exit();
                }

                $id_marca = intval($_POST['id_marca'] ?? 0);
                $nombre_marca = trim($_POST['nombre_marca'] ?? '');
                $cliente_asociado = trim($_POST['cliente_asociado'] ?? '');
                $estado = intval($_POST['estado'] ?? 1);
                $id_responsable = intval($_POST['id_responsable'] ?? 0);

                if ($id_marca > 0 && !empty($nombre_marca) && !empty($cliente_asociado) && $id_responsable > 0) {
                    if ($brandModel->updateBrandAndProject($id_marca, $nombre_marca, $cliente_asociado, $estado, $id_responsable)) {
                        $mensaje = "Marca y proyecto actualizados correctamente.";
                    } else {
                        $error = "Error al actualizar la marca.";
                    }
                } else {
                    $error = "Complete todos los campos para actualizar.";
                }

            } elseif ($action === 'assign_workers_to_project') {
                // TANTO ADMIN (1) COMO RESPONSABLE (2) PUEDEN ASIGNAR TRABAJADORES
                $id_proyecto = intval($_POST['id_proyecto'] ?? 0);
                $trabajadores = $_POST['trabajadores_proyecto'] ?? [];

                if ($id_proyecto > 0 && !empty($trabajadores)) {
                    if ($brandModel->assignWorkersToProject($id_proyecto, $trabajadores)) {
                        $mensaje = "Trabajadores asignados correctamente al proyecto.";
                    } else {
                        $error = "Error al asignar los trabajadores.";
                    }
                } else {
                    $error = "Debe seleccionar al menos un trabajador.";
                }

            } elseif ($action === 'remove_worker') {
                // TANTO ADMIN (1) COMO RESPONSABLE (2) PUEDENremover trabajadores
                $id_proyecto = intval($_POST['id_proyecto'] ?? 0);
                $id_usuario = intval($_POST['id_usuario'] ?? 0);

                if ($id_proyecto > 0 && $id_usuario > 0) {
                    if ($brandModel->removeWorkerFromProject($id_proyecto, $id_usuario)) {
                        $mensaje = "Trabajador removido exitosamente del proyecto.";
                    } else {
                        $error = "Error al remover al trabajador.";
                    }
                }
            }
        }

        $brands = $brandModel->getAllBrands();
        foreach ($brands as &$brand) {
            $brand['proyectos'] = $brandModel->getProjectsByBrand($brand['id_marca']);
        }
        unset($brand);

        $allEmployees = $userModel->getAllUsers();

        require_once 'views/brands.php';
    }
}
?>