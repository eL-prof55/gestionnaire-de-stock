<div class="container">
    <h2>➕ Nouvel article</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nom de l'article</label>
            <input type="text" name="nom" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>

        <div class="form-group">
            <label>Prix unitaire (FCFA)</label>
            <input type="number" name="prix_unitaire" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Quantité</label>
            <input type="number" name="quantite" value="0" required>
        </div>

        <div class="form-group">
            <label>Seuil minimum</label>
            <input type="number" name="seuil_min" value="5" required>
        </div>

        <div class="form-group">
            <label>Catégorie</label>
            <select name="id_categorie">
                <option value="">-- Aucune catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>">
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
                    <option value="<?= $f['id'] ?>">
                        <?= htmlspecialchars($f['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="form-actions">
            <button type="submit">➕ Ajouter</button>
            <a href="index.php?page=articles">❌ Annuler</a>
        </div>
    </form>
</div>