<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/Database.php';
require_once 'models/Project.php';

class ProjectsController {

    public function index() {
        // Verificar sesión y permitir Administradores (1) y Responsables (2)
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
            header("Location: index.php?controller=dashboard&action=index");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $projectModel = new Project($db);

        $mensaje = '';
        $error = '';

        // Capturar filtros de marca y fecha (por defecto la fecha de hoy)
       $id_marca = intval($_GET['id_marca'] ?? 0);
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $usar_rango = isset($_GET['usar_rango']) ? true : false;
        $fecha_inicio = $_GET['fecha_inicio'] ?? $fecha;
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');

        // Procesar acciones administrativas/supervisión (Permitir Admin 1 y Responsable 2)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)) {
            $action = $_POST['action_type'] ?? '';
            $id_actividad = intval($_POST['id_actividad'] ?? 0);

            if ($action === 'accept_report') {
                if ($projectModel->resolveObservationsAndAccept($id_actividad)) {
                    $mensaje = "Reporte aceptado exitosamente y observaciones cerradas.";
                } else {
                    $error = "Error al actualizar el estado del reporte.";
                }
            } elseif ($action === 'observe_report') {
                $comentario = trim($_POST['comentario'] ?? '');
                $id_usuario = $_SESSION['user_id'];

                if ($id_actividad > 0 && !empty($comentario)) {
                    if ($projectModel->addObservation($id_actividad, $id_usuario, $comentario)) {
                        $mensaje = "Observación agregada y reporte marcado como observado.";
                    } else {
                        $error = "Error al registrar la observación.";
                    }
                } else {
                    $error = "La observación no puede estar vacía.";
                }
            }
        }

        $brands = $projectModel->getAllBrands();
        $selectedProject = null;
        $reports = [];

if ($id_marca > 0) {
            $selectedProject = $projectModel->getProjectByBrand($id_marca);
            if ($selectedProject) {
                if ($usar_rango) {
                    // Si el rango está activo, pasamos fecha_inicio y fecha_fin
                    $reports = $projectModel->getReportsByProjectAndDate($selectedProject['id_proyecto'], $fecha_inicio, $fecha_fin);
                } else {
                    // Si es fecha única
                    $reports = $projectModel->getReportsByProjectAndDate($selectedProject['id_proyecto'], $fecha);
                }
            }
        }

        require_once 'views/projects.php';
    }
}
?>
