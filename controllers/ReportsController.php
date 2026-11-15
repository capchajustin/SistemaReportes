<?php
require_once 'config/database.php';
require_once 'models/Activity.php';

class ReportsController {

    public function myReports() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $activityModel = new Activity($db);

        $id_usuario = $_SESSION['user_id'];

        $myReports = $activityModel->getActivitiesByUser($id_usuario);

        require_once 'views/my_reports.php';
    }
}
?>