<?php
require_once __DIR__ . '/../controllers/articlecontroller.php';

header('Content-Type: application/json');

$controller = new ArticleController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getAll':
        $articles = $controller->getAllArticles();
        echo json_encode(['success' => true, 'data' => $articles]);
        break;

    case 'create':
        $result = $controller->store($_POST, $_FILES);
        echo json_encode($result);
        break;

    case 'update':
        $id = $_POST['id'] ?? 0;
        $result = $controller->update($id, $_POST, $_FILES);
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
