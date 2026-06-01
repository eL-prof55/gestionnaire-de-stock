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
        <h1><i class="icon-plus-circle"></i> Ajouter un Article</h1>
    </div>

    <div class="card form-card">
        <form id="form-article" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group form-group-large">
                    <label>Nom de l'article *</label>
                    <input type="text" name="nom" required placeholder="Ex: Ordinateur Portable HP">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Description détaillée..."></textarea>
            </div>

            <div class="form-row three-cols">
                <div class="form-group">
                    <label>Prix unitaire (FCFA) *</label>
                    <input type="number" name="prix_unitaire" min="0" step="0.01" required placeholder="150000">
                </div>
                <div class="form-group">
                    <label>Quantité initiale *</label>
                    <input type="number" name="quantite" min="0" required placeholder="10">
                </div>
                <div class="form-group">
                    <label>Seuil minimum d'alerte</label>
                    <input type="number" name="seuil_min" min="1" value="5" placeholder="5">
                    <small class="hint">Alerte quand le stock passe sous ce seuil</small>
                </div>
            </div>

            <div class="form-row two-cols">
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="id_categorie">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fournisseur</label>
                    <select name="id_fournisseur">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($fournisseurs as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group upload-group">
                <label>Image du produit</label>
                <div class="upload-zone" id="upload-zone">
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                           id="image-input" class="file-input">
                    <div class="upload-placeholder" id="upload-placeholder">
                        <i class="icon-upload"></i>
                        <span>Cliquez ou glissez une image ici</span>
                        <small>JPG, PNG, WebP • Max 2 Mo</small>
                    </div>
                    <div id="image-preview" class="image-preview"></div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="icon-check"></i> Créer l'article
                </button>
                <a href="index.php?page=articles" class="btn btn-secondary">
                    <i class="icon-x"></i> Annuler
                </a>
            </div>

        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
