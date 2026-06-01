<?php
require_once '../config/db.php';

// Récupérer tous les articles
$stmt = $pdo->query("
    SELECT a.*, c.nom AS categorie_nom, f.nom AS fournisseur_nom
    FROM articles a
    LEFT JOIN categories c ON a.id_categorie = c.id
    LEFT JOIN fournisseurs f ON a.id_fournisseur = f.id
    ORDER BY a.nom ASC
");
$articles = $stmt->fetchAll();

// Dire au navigateur que c'est un fichier XML à télécharger
header('Content-Type: application/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="stock_' . date('Y-m-d') . '.xml"');

// Générer le XML
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><stock></stock>');
$xml->addAttribute('date_export', date('Y-m-d'));
$xml->addAttribute('total_articles', count($articles));

foreach ($articles as $article) {
    $node = $xml->addChild('article');
    $node->addAttribute('id', $article['id']);
    $node->addChild('nom', htmlspecialchars($article['nom']));
    $node->addChild('description', htmlspecialchars($article['description'] ?? ''));
    $node->addChild('prix_unitaire', $article['prix_unitaire']);
    $node->addChild('quantite', $article['quantite']);
    $node->addChild('seuil_min', $article['seuil_min']);
    $node->addChild('categorie', htmlspecialchars($article['categorie_nom'] ?? ''));
    $node->addChild('fournisseur', htmlspecialchars($article['fournisseur_nom'] ?? ''));
    $node->addChild('statut', $article['quantite'] < $article['seuil_min'] ? 'alerte' : 'normal');
}

echo $xml->asXML();