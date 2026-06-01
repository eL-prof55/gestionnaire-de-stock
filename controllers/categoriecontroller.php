<?php
require_once __DIR__ . '/../models/categorie.php';

class CategorieController {
    private $model;

    public function __construct() {
        $this->model = new Categorie();
    }

    public function index() {
        $categories = $this->model->getAll();
        require __DIR__ . '/../views/categories/index.php';
    }

    public function store($data) {
        if (empty($data['nom'])) {
            return ['success' => false, 'message' => 'Le nom de la catégorie est obligatoire.'];
        }

        $all = $this->model->getAll();
        foreach ($all as $cat) {
            if (strtolower($cat['nom']) === strtolower($data['nom'])) {
                return ['success' => false, 'message' => 'Cette catégorie existe déjà.'];
            }
        }

        $result = $this->model->create($data['nom'], $data['description'] ?? '');
        if ($result) {
            return ['success' => true, 'message' => 'Catégorie créée avec succès.'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la création.'];
    }

    public function update($id, $data) {
        if (empty($data['nom'])) {
            return ['success' => false, 'message' => 'Le nom de la catégorie est obligatoire.'];
        }

        $all = $this->model->getAll();
        foreach ($all as $cat) {
            if (strtolower($cat['nom']) === strtolower($data['nom']) && $cat['id'] != $id) {
                return ['success' => false, 'message' => 'Cette catégorie existe déjà.'];
            }
        }

        $result = $this->model->update($id, $data['nom'], $data['description'] ?? '');
        if ($result) {
            return ['success' => true, 'message' => 'Catégorie modifiée avec succès.'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la modification.'];
    }

    public function delete($id) {
        $count = $this->model->compterAR($id);
        if ($count > 0) {
            return ['success' => false, 'message' => 'Impossible : cette catégorie contient ' . $count . ' article(s).'];
        }

        $result = $this->model->delete($id);
        if ($result) {
            return ['success' => true, 'message' => 'Catégorie supprimée avec succès.'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la suppression.'];
    }
}
