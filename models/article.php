<?php
class Article {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les articles avec leur catégorie et fournisseur
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT a.*, c.nom AS categorie_nom, f.nom AS fournisseur_nom
            FROM articles a
            LEFT JOIN categories c ON a.id_categorie = c.id
            LEFT JOIN fournisseurs f ON a.id_fournisseur = f.id
            ORDER BY a.nom ASC
        ");
        return $stmt->fetchAll();
    }

    // Récupérer un article par son id
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Créer un nouvel article
    public function create($nom, $description, $prix, $quantite, $seuil, $image, $id_cat, $id_four) {
        $stmt = $this->pdo->prepare("
            INSERT INTO articles (nom, description, prix_unitaire, quantite, seuil_min, image, id_categorie, id_fournisseur)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $description, $prix, $quantite, $seuil, $image, $id_cat, $id_four]);
    }

    // Modifier un article
    public function update($id, $nom, $description, $prix, $quantite, $seuil, $image, $id_cat, $id_four) {
        $stmt = $this->pdo->prepare("
            UPDATE articles 
            SET nom=?, description=?, prix_unitaire=?, quantite=?, seuil_min=?, image=?, id_categorie=?, id_fournisseur=?
            WHERE id=?
        ");
        return $stmt->execute([$nom, $description, $prix, $quantite, $seuil, $image, $id_cat, $id_four, $id]);
    }

    // Supprimer un article
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id=?");
        return $stmt->execute([$id]);
    }
}