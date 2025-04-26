<?php
session_start();
require_once __DIR__ . '/config/paths.php'; // Cargar primero las constantes de rutas

// Ahora puedes usar las constantes para incluir otros archivos
require_once CONFIG_PATH . '/database.php';
require_once CONTROLLERS_PATH . '/EstudiantesController.php';

$controller = new EstudiantesController();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch($action) {
    case 'index':
    default:
        $controller->index();
        break;
        
    case 'crear':
        $controller->mostrarFormulario();
        break;
        
    case 'editar':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if($id) {
            $controller->mostrarFormulario($id);
        } else {
            $_SESSION['error'] = "ID de estudiante no proporcionado";
            $controller->index();
        }
        break;
        
    case 'guardar':
        $controller->guardar();
        break;
        
    case 'eliminar':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $controller->eliminar($id);
        break;
}
?>