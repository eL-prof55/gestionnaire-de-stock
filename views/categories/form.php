<div class="container">
    <h2><?= isset($categorie) ? '✏️ Modifier la catégorie' : '➕ Nouvelle catégorie' ?></h2>

    <form method="POST">
        <div class="form-group">
            <label>Nom de la catégorie</label>
            <input 
                type="text" 
                name="nom" 
                required
                value="<?= isset($categorie) ? htmlspecialchars($categorie['nom']) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description">
                <?= isset($categorie) ? htmlspecialchars($categorie['description'] ?? '') : '' ?>
            </textarea>
        </div>

        <div class="form-actions">
            <button type="submit">
                <?= isset($categorie) ? '✏️ Modifier' : '➕ Ajouter' ?>
            </button>
            <a href="index.php?page=categories">❌ Annuler</a>
        </div>
    </form>
</div>