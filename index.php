<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
// Enrutador básico en index.php
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Mapeo de controladores
switch ($controllerName) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        break;
    case 'auth':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        break;
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        break;
    case 'profile': // <-- NUEVO MÓDULO DE PERFIL
        require_once 'controllers/ProfileController.php';
        $controller = new ProfileController();
        break;
    case 'employees':
        require_once 'controllers/EmployeesController.php';
        $controller = new EmployeesController();
        break;
        case 'brands': 
        require_once 'controllers/BrandsController.php';
        $controller = new BrandsController();
        break;
        case 'projects':
        require_once 'controllers/ProjectsController.php';
        $controller = new ProjectsController();
        break;
        case 'activities':
        require_once 'controllers/ActivitiesController.php';
        $controller = new ActivitiesController();
        break;
        case 'reports': 
        require_once 'controllers/ReportsController.php';
        $controller = new ReportsController();
        break;
        default:
        // Manejo de error 404 o redirección
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        break;

}

// Verificar si la acción existe en el controlador, si no, usar 'index' o 'login'
if (method_exists($controller, $actionName)) {
    $controller->$actionName();
} else {
    if (method_exists($controller, 'index')) {
        $controller->index();
    } else {
        echo "Error: La acción solicitada no existe.";
    }
}
?>