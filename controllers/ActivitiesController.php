<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/Database.php';
require_once 'models/Activity.php';

class ActivitiesController {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $activityModel = new Activity($db);

        $mensaje = '';
        $error = '';
        $id_usuario = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'create_report') {
            $id_proyecto = intval($_POST['id_proyecto'] ?? 0);
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $porcentaje_avance = floatval($_POST['porcentaje_avance'] ?? 0);
            $archivo = $_FILES['evidencia'] ?? null;

            if ($id_proyecto > 0 && !empty($titulo) && !empty($descripcion) && $porcentaje_avance >= 0 && $porcentaje_avance <= 100) {
                if ($activityModel->createActivityWithEvidence($id_proyecto, $id_usuario, $titulo, $descripcion, $porcentaje_avance, $archivo)) {
                    $mensaje = "Reporte diario guardado exitosamente con su avance y evidencia.";
                } else {
                    $error = "Error al guardar el reporte en la base de datos.";
                }
            } else {
                $error = "Por favor, completa todos los campos requeridos y asegúrate de que el porcentaje esté entre 0 y 100.";
            }
        }

        $myProjects = $activityModel->getProjectsByUser($id_usuario);

        require_once 'views/activities.php';
    }
}
?>
