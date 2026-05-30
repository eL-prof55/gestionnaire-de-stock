<?php
session_start();
require_once '../config/db.php';
require_once '../models/categorie.php';

header('Content-Type: application/json');

$model = new Categorie($pdo);
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'getAll':
        $data = $model->getAll();
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'create':
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
            break;
        }
        $ok = $model->create($nom, $description);
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Catégorie créée' : 'Erreur lors de la création'
        ]);
        break;

    case 'update':
        $id = $_POST['id'] ?? null;
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (!$id || empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            break;
        }
        $ok = $model->update($id, $nom, $description);
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Catégorie modifiée' : 'Erreur lors de la modification'
        ]);
        break;

    case 'delete':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID manquant']);
            break;
        }
        $ok = $model->delete($id);
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Catégorie supprimée' : 'Erreur lors de la suppression'
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action inconnue']);
}