<div class="container">
    <div class="header">
        <h2>🛍️ Articles</h2>
        <a href="index.php?page=articles&action=create" class="btn-ajouter">
            + Ajouter un article
        </a>
    </div>

    <?php if (empty($articles)): ?>
        <p>Aucun article trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Seuil min</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr>
                    <td>
                        <?php if ($article['image']): ?>
                            <img 
                                src="assets/uploads/articles/<?= htmlspecialchars($article['image']) ?>" 
                                width="50"
                            >
                        <?php else: ?>
                            Pas d'image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($article['nom']) ?></td>
                    <td><?= htmlspecialchars($article['categorie_nom'] ?? 'Aucune') ?></td>
                    <td><?= number_format($article['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= $article['quantite'] ?></td>
                    <td><?= $article['seuil_min'] ?></td>
                    <td>
                        <a href="index.php?page=articles&action=edit&id=<?= $article['id'] ?>">
                            ✏️ Modifier
                        </a>
                        <a href="index.php?page=articles&action=delete&id=<?= $article['id'] ?>"
                           onclick="return confirm('Supprimer cet article ?')">
                            🗑️ Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>