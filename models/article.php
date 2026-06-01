<?php
require_once __DIR__ . '/../config/db.php';

class Article {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $sql = "SELECT a.*, c.nom AS nom_categorie, f.nom AS nom_fournisseur 
                FROM articles a 
                LEFT JOIN categories c ON a.id_categorie = c.id 
                LEFT JOIN fournisseurs f ON a.id_fournisseur = f.id 
                ORDER BY a.created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function exists($nom) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM articles WHERE nom = ?");
        $stmt->execute([$nom]);
        return $stmt->fetchColumn() > 0;
    }

    public function create($data) {
        $sql = "INSERT INTO articles (nom, description, prix_unitaire, quantite, seuil_min, image, id_categorie, id_fournisseur) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['prix_unitaire'],
            $data['quantite'],
            $data['seuil_min'],
            $data['image'],
            $data['id_categorie'],
            $data['id_fournisseur']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE articles 
                SET nom = ?, description = ?, prix_unitaire = ?, quantite = ?, seuil_min = ?, image = ?, id_categorie = ?, id_fournisseur = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['prix_unitaire'],
            $data['quantite'],
            $data['seuil_min'],
            $data['image'],
            $data['id_categorie'],
            $data['id_fournisseur'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAlertes() {
        $stmt = $this->pdo->query("SELECT * FROM articles WHERE quantite <= seuil_min ORDER BY quantite ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
