<?php
require_once __DIR__ . '/../config/db.php';

class Categorie {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nom, $description = '') {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom, description) VALUES (?, ?)");
        return $stmt->execute([$nom, $description]);
    }

    public function update($id, $nom, $description = '') {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom = ?, description = ? WHERE id = ?");
        return $stmt->execute([$nom, $description, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function compterAR($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM articles WHERE id_categorie = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}
