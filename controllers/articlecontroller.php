<?php
require_once __DIR__ . '/../models/article.php';
require_once __DIR__ . '/../models/categorie.php';
require_once __DIR__ . '/../models/fournisseur.php';

class ArticleController {
    private $model;
    private $modelCat;
    private $modelFourn;

    public function __construct() {
        $this->model = new Article();
        $this->modelCat = new Categorie();
        $this->modelFourn = new Fournisseur();
    }

    public function index() {
        $articles = $this->model->getAll();
        require __DIR__ . '/../views/articles/index.php';
    }

    public function create() {
        $categories = $this->modelCat->getAll();
        $fournisseurs = $this->modelFourn->getAll();
        require __DIR__ . '/../views/articles/create.php';
    }

    public function edit($id) {
        $article = $this->model->getById($id);
        if (!$article) {
            header('Location: index.php?page=articles');
            exit;
        }
        $categories = $this->modelCat->getAll();
        $fournisseurs = $this->modelFourn->getAll();
        require __DIR__ . '/../views/articles/edit.php';
    }

    public function store($data, $files) {
        if (empty($data['nom']) || empty($data['prix_unitaire']) || !isset($data['quantite'])) {
            return ['success' => false, 'message' => 'Les champs nom, prix et quantité sont obligatoires.'];
        }

        if ($this->model->exists($data['nom'])) {
            return ['success' => false, 'message' => 'Un article avec ce nom existe déjà.'];
        }

        $imageName = null;
        if (!empty($files['image']['tmp_name'])) {
            $uploadResult = $this->uploadImage($files['image']);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }
            $imageName = $uploadResult['filename'];
        }

        $articleData = [
            'nom' => $data['nom'],
            'description' => $data['description'] ?? '',
            'prix_unitaire' => $data['prix_unitaire'],
            'quantite' => $data['quantite'],
            'seuil_min' => $data['seuil_min'] ?? 5,
            'image' => $imageName,
            'id_categorie' => !empty($data['id_categorie']) ? $data['id_categorie'] : null,
            'id_fournisseur' => !empty($data['id_fournisseur']) ? $data['id_fournisseur'] : null
        ];

        $result = $this->model->create($articleData);
        if ($result) {
            return ['success' => true, 'message' => 'Article créé avec succès.'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la création.'];
    }

    public function update($id, $data, $files) {
        $article = $this->model->getById($id);
        if (!$article) {
            return ['success' => false, 'message' => 'Article non trouvé.'];
        }

        $imageName = $article['image'];

        if (!empty($files['image']['tmp_name'])) {
            $uploadResult = $this->uploadImage($files['image']);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }
            if ($imageName && file_exists(__DIR__ . '/../assets/uploads/articles/' . $imageName)) {
                unlink(__DIR__ . '/../assets/uploads/articles/' . $imageName);
            }
            $imageName = $uploadResult['filename'];
        }

        $articleData = [
            'nom' => $data['nom'],
            'description' => $data['description'] ?? '',
            'prix_unitaire' => $data['prix_unitaire'],
            'quantite' => $data['quantite'],
            'seuil_min' => $data['seuil_min'] ?? 5,
            'image' => $imageName,
            'id_categorie' => !empty($data['id_categorie']) ? $data['id_categorie'] : null,
            'id_fournisseur' => !empty($data['id_fournisseur']) ? $data['id_fournisseur'] : null
        ];

        $result = $this->model->update($id, $articleData);
        if ($result) {
            return ['success' => true, 'message' => 'Article modifié avec succès.'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la modification.'];
    }

    public function delete($id) {
        $article = $this->model->getById($id);
        if (!$article) {
            return ['success' => false, 'message' => 'Article non trouvé.'];
        }

        if ($article['image'] && file_exists(__DIR__ . '/../assets/uploads/articles/' . $article['image'])) {
            unlink(__DIR__ . '/../assets/uploads/articles/' . $article['image']);
        }

        $result = $this->model->delete($id);
        if ($result) {
            return ['success' => true, 'message' => 'Article supprimé avec succès.'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la suppression.'];
    }

    private function uploadImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Format non valide. Utilisez JPG, PNG ou WebP.'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Image trop lourde. Taille maximum : 2 Mo.'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'article_' . time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = __DIR__ . '/../assets/uploads/articles/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        }
        return ['success' => false, 'message' => 'Erreur lors de l'upload.'];
    }
}
