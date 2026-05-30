<?php
class Categorie {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer toutes les catégories
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY nom ASC");
        return $stmt->fetchAll();
    }

    // Récupérer une catégorie par son id
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Créer une nouvelle catégorie
    public function create($nom, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom, description) VALUES (?, ?)");
        return $stmt->execute([$nom, $description]);
    }

    // Modifier une catégorie
    public function update($id, $nom, $description) {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom=?, description=? WHERE id=?");
        return $stmt->execute([$nom, $description, $id]);
    }

    // Supprimer une catégorie
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id=?");
        return $stmt->execute([$id]);
    }
}