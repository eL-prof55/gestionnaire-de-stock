<?php
require_once __DIR__ . '/../config/db.php';

class Fournisseur {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
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

    public function create($nom, $telephone = null, $email = null, $addresse = null) {
        $stmt = $this->pdo->prepare("INSERT INTO fournisseurs (nom, telephone, email, addresse) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nom, $telephone, $email, $addresse]);
    }

    public function update($id, $nom, $telephone = null, $email = null, $addresse = null) {
        $stmt = $this->pdo->prepare("UPDATE fournisseurs SET nom = ?, telephone = ?, email = ?, addresse = ? WHERE id = ?");
        return $stmt->execute([$nom, $telephone, $email, $addresse, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM fournisseurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
