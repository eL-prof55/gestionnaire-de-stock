<?php
require_once 'models/categorie.php';

class CategorieController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Categorie($pdo);
    }

    // Afficher la liste des catégories
    public function index() {
        $categories = $this->model->getAll();
        require 'views/categories/index.php';
    }

    // Afficher le formulaire + traiter la création
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description'] ?? '');
            $this->model->create($nom, $description);
            header('Location: index.php?page=categories');
            exit;
        }
        require 'views/categories/form.php';
    }

    // Afficher le formulaire + traiter la modification
    public function edit($id) {
        $categorie = $this->model->getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description'] ?? '');
            $this->model->update($id, $nom, $description);
            header('Location: index.php?page=categories');
            exit;
        }
        require 'views/categories/form.php';
    }

    // Supprimer une catégorie
    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?page=categories');
        exit;
    }
}