<?php
require_once __DIR__ . '/../config/db.php';

class Fournisseur {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnexion();
    }
    
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM fournisseurs ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM fournisseurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($nom, $telephone, $email, $adresse) {
        $stmt = $this->pdo->prepare("INSERT INTO fournisseurs (nom, telephone, email, adresse) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nom, $telephone, $email, $adresse]);
    }
    
    public function update($id, $nom, $telephone, $email, $adresse) {
        $stmt = $this->pdo->prepare("UPDATE fournisseurs SET nom=?, telephone=?, email=?, adresse=? WHERE id=?");
        return $stmt->execute([$nom, $telephone, $email, $adresse, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE articles SET id_fournisseur = NULL WHERE id_fournisseur = ?");
        $stmt->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM fournisseurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function countArticles($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM articles WHERE id_fournisseur = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>