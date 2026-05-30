<?php
require_once 'models/article.php';
require_once 'models/categorie.php';
require_once 'models/fournisseur.php';

class ArticleController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->model = new Article($pdo);
        $this->pdo = $pdo;
    }

    // Afficher la liste des articles
    public function index() {
        $articles = $this->model->getAll();
        require 'views/articles/index.php';
    }

    // Afficher formulaire + traiter la création
    public function create() {
        $categories = (new Categorie($this->pdo))->getAll();
        $fournisseurs = (new Fournisseur($this->pdo))->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $this->handleUpload();
            $this->model->create(
                trim($_POST['nom']),
                trim($_POST['description'] ?? ''),
                $_POST['prix_unitaire'],
                $_POST['quantite'],
                $_POST['seuil_min'],
                $image,
                $_POST['id_categorie'] ?: null,
                $_POST['id_fournisseur'] ?: null
            );
            header('Location: index.php?page=articles');
            exit;
        }
        require 'views/articles/form.php';
    }

    // Afficher formulaire + traiter la modification
    public function edit($id) {
        $article = $this->model->getById($id);
        $categories = (new Categorie($this->pdo))->getAll();
        $fournisseurs = (new Fournisseur($this->pdo))->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $this->handleUpload() ?? $article['image'];
            $this->model->update(
                $id,
                trim($_POST['nom']),
                trim($_POST['description'] ?? ''),
                $_POST['prix_unitaire'],
                $_POST['quantite'],
                $_POST['seuil_min'],
                $image,
                $_POST['id_categorie'] ?: null,
                $_POST['id_fournisseur'] ?: null
            );
            header('Location: index.php?page=articles');
            exit;
        }
        require 'views/articles/form.php';
    }

    // Supprimer un article
    public function delete($id) {
        $article = $this->model->getById($id);
        if ($article['image']) {
            $path = 'assets/uploads/articles/' . $article['image'];
            if (file_exists($path)) unlink($path);
        }
        $this->model->delete($id);
        header('Location: index.php?page=articles');
        exit;
    }

    // Gérer l'upload d'image
    private function handleUpload() {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mime = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($mime, $allowed)) return null;
        if ($_FILES['image']['size'] > 2 * 1024 * 1024) return null;

        $nomFichier = 'article_' . time() . '_' . basename($_FILES['image']['name']);
        $destination = 'assets/uploads/articles/' . $nomFichier;
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        return $nomFichier;
    }
}