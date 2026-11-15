<?php
class DashboardController {
    public function index() {
        // Validar si el usuario ha iniciado sesión
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        require_once 'views/dashboard.php';
    }
}
?>