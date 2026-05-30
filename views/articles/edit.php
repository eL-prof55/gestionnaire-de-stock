<div class="container">
    <h2>✏️ Modifier l'article</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nom de l'article</label>
            <input type="text" name="nom" required
                value="<?= htmlspecialchars($article['nom']) ?>">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description">
                <?= htmlspecialchars($article['description'] ?? '') ?>
            </textarea>
        </div>

        <div class="form-group">
            <label>Prix unitaire (FCFA)</label>
            <input type="number" name="prix_unitaire" step="0.01" required
                value="<?= $article['prix_unitaire'] ?>">
        </div>

        <div class="form-group">
            <label>Quantité</label>
            <input type="number" name="quantite" required
                value="<?= $article['quantite'] ?>">
        </div>

        <div class="form-group">
            <label>Seuil minimum</label>
            <input type="number" name="seuil_min" required
                value="<?= $article['seuil_min'] ?>">
        </div>

        <div class="form-group">
            <label>Catégorie</label>
            <select name="id_categorie">
                <option value="">-- Aucune catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= $cat['id'] == $article['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Fournisseur</label>
            <select name="id_fournisseur">
                <option value="">-- Aucun fournisseur --</option>
                <?php foreach ($fournisseurs as $f): ?>
                    <option value="<?= $f['id'] ?>"
                        <?= $f['id'] == $article['id_fournisseur'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Image actuelle</label><br>
            <?php if ($article['image']): ?>
                <img src="assets/uploads/articles/<?= htmlspecialchars($article['image']) ?>" width="100">
            <?php else: ?>
                <p>Aucune image</p>
            <?php endif; ?>
            <label>Changer l'image</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="form-actions">
            <button type="submit">✏️ Modifier</button>
            <a href="index.php?page=articles">❌ Annuler</a>
        </div>
    </form>
</div>