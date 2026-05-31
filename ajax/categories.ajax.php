<?php
require_once __DIR__ . '/../controllers/categoriecontroller.php';

header('Content-Type: application/json');

$controller = new CategorieController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getAll':
        $categories = $controller->model->getAll();
        echo json_encode(['success' => true, 'data' => $categories]);
        break;

    case 'create':
        $result = $controller->store($_POST);
        echo json_encode($result);
        break;

    case 'update':
        $id = $_POST['id'] ?? 0;
        $result = $controller->update($id, $_POST);
        echo json_encode($result);
        break;

    case 'delete':
        $id = $_POST['id'] ?? 0;
        $result = $controller->delete($id);
        echo json_encode($result);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action non reconnue.']);
}
