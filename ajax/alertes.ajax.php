<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'check':
        // Récupérer tous les articles sous le seuil minimum
        $stmt = $pdo->query("
            SELECT a.nom, a.quantite, a.seuil_min, c.nom AS categorie
            FROM articles a
            LEFT JOIN categories c ON a.id_categorie = c.id
            WHERE a.quantite < a.seuil_min
            ORDER BY a.quantite ASC
        ");
        $alertes = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'total'   => count($alertes),
            'data'    => $alertes
        ]);
        break;

    default:
        echo json_encode([
            'success' => false,
            'message' => 'Action inconnue'
        ]);
}