<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

require __DIR__ . '/../layout/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="icon-edit-2"></i> Modifier l'Article</h1>
    </div>

    <div class="card form-card">
        <form id="form-article-edit" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $article['id'] ?>">

            <div class="form-row">
                <div class="form-group form-group-large">
                    <label>Nom de l'article *</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($article['nom']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($article['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row three-cols">
                <div class="form-group">
                    <label>Prix unitaire (FCFA) *</label>
                    <input type="number" name="prix_unitaire" value="<?= $article['prix_unitaire'] ?>"
                           min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Quantité *</label>
                    <input type="number" name="quantite" value="<?= $article['quantite'] ?>"
                           min="0" required>
                </div>
                <div class="form-group">
                    <label>Seuil minimum d'alerte</label>
                    <input type="number" name="seuil_min" value="<?= $article['seuil_min'] ?>" min="1">
                </div>
            </div>

            <div class="form-row two-cols">
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="id_categorie">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= ($article['id_categorie'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fournisseur</label>
                    <select name="id_fournisseur">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($fournisseurs as $f): ?>
                        <option value="<?= $f['id'] ?>"
                            <?= ($article['id_fournisseur'] == $f['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group upload-group">
                <label>Image du produit</label>

                <?php if ($article['image']): ?>
                <div class="current-image-container">
                    <p class="image-label">Image actuelle :</p>
                    <img src="assets/uploads/articles/<?= htmlspecialchars($article['image']) ?>"
                         class="current-image" alt="Image actuelle">
                </div>
                <?php endif; ?>

                <div class="upload-zone" id="upload-zone">
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                           id="image-input" class="file-input">
                    <div class="upload-placeholder" id="upload-placeholder">
                        <i class="icon-upload"></i>
                        <span><?= $article['image'] ? 'Remplacer l'image' : 'Ajouter une image' ?></span>
                        <small>JPG, PNG, WebP • Max 2 Mo • Laisser vide pour conserver l'actuelle</small>
                    </div>
                    <div id="image-preview" class="image-preview"></div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="icon-check"></i> Mettre à jour
                </button>
                <a href="index.php?page=articles" class="btn btn-secondary">
                    <i class="icon-x"></i> Annuler
                </a>
            </div>

        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
