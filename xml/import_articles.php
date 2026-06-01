<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['fichier_xml'])) {
    header('Location: ../index.php?page=rapports');
    exit;
}

// Vérifier que c'est bien un fichier XML
if ($_FILES['fichier_xml']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../index.php?page=rapports&erreur=upload');
    exit;
}

// Lire le fichier XML
$xml = simplexml_load_file($_FILES['fichier_xml']['tmp_name']);

if (!$xml) {
    header('Location: ../index.php?page=rapports&erreur=xml_invalide');
    exit;
}

$importes = 0;
$ignores  = 0;

foreach ($xml->article as $article) {
    $nom = trim((string) $article->nom);

    if (empty($nom)) {
        $ignores++;
        continue;
    }

    // Vérifier si l'article existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE nom = ?");
    $stmt->execute([$nom]);
    if ($stmt->fetchColumn() > 0) {
        $ignores++;
        continue;
    }

    // Vérifier si la catégorie existe, sinon la créer
    $categorie_nom = trim((string) $article->categorie);
    $id_categorie  = null;

    if (!empty($categorie_nom)) {
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE nom = ?");
        $stmt->execute([$categorie_nom]);
        $cat = $stmt->fetch();

        if ($cat) {
            $id_categorie = $cat['id'];
        } else {
            // Créer la catégorie automatiquement
            $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
            $stmt->execute([$categorie_nom]);
            $id_categorie = $pdo->lastInsertId();
        }
    }

    // Insérer l'article
    $stmt = $pdo->prepare("
        INSERT INTO articles (nom, description, prix_unitaire, quantite, seuil_min, id_categorie)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $nom,
        trim((string) $article->description),
        (float) $article->prix_unitaire,
        (int)   $article->quantite,
        (int)   $article->seuil_min ?: 5,
        $id_categorie
    ]);
    $importes++;
}

// Rediriger avec le résultat
header("Location: ../index.php?page=rapports&importes=$importes&ignores=$ignores");
exit;