<?php
session_start();
require_once '../config/db.php';
require_once '../models/article.php';

header('Content-Type: application/json');

$model = new Article($pdo);
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'getAll':
        $data = $model->getAll();
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'create':
        $nom = trim($_POST['nom'] ?? '');
        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
            break;
        }
        $ok = $model->create(
            $nom,
            trim($_POST['description'] ?? ''),
            $_POST['prix_unitaire'] ?? 0,
            $_POST['quantite'] ?? 0,
            $_POST['seuil_min'] ?? 5,
            null,
            $_POST['id_categorie'] ?: null,
            $_POST['id_fournisseur'] ?: null
        );
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Article créé' : 'Erreur lors de la création'
        ]);
        break;

    case 'update':
        $id = $_POST['id'] ?? null;
        $nom = trim($_POST['nom'] ?? '');
        if (!$id || empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            break;
        }
        $article = $model->getById($id);
        $ok = $model->update(
            $id,
            $nom,
            trim($_POST['description'] ?? ''),
            $_POST['prix_unitaire'] ?? 0,
            $_POST['quantite'] ?? 0,
            $_POST['seuil_min'] ?? 5,
            $article['image'],
            $_POST['id_categorie'] ?: null,
            $_POST['id_fournisseur'] ?: null
        );
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Article modifié' : 'Erreur lors de la modification'
        ]);
        break;

    case 'delete':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID manquant']);
            break;
        }
        $article = $model->getById($id);
        if ($article['image']) {
            $path = '../assets/uploads/articles/' . $article['image'];
            if (file_exists($path)) unlink($path);
        }
        $ok = $model->delete($id);
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Article supprimé' : 'Erreur lors de la suppression'
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action inconnue']);
}