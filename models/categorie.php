<?php
require_once __DIR__ . '/config/db.example.php';

class categories {
    private $pdo;

    public function__construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
   
    //Recuperer toutes les categories

    public function getall() {
        $stmt = $this->pdo->prepare("SELECT * FROM categories order by nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //creer un categorie

    public function create($nom) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom) VALUES (:nom)");
        $stmt->bindParam(':nom', $nom); //
        return $stmt->execute();
    }

    //modifier une categorie

    public function update($id, $nom) {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom = :nom WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        return $stmt->execute();
    }

    //supprimer une categorie

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    //compter les articles dans une categorie
    //pour empercher la suppression d'une categorie qui contient des articles

    public function compterAR($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM articles WHERE categorie_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute([$id]);
        return $stmt->fetchcolumn();
    }
}