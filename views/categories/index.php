<div class="container">
    <div class="header">
        <h2>📦 Catégories</h2>
        <a href="index.php?page=categories&action=create" class="btn-ajouter">
            + Ajouter une catégorie
        </a>
    </div>

    <?php if (empty($categories)): ?>
        <p>Aucune catégorie trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= htmlspecialchars($cat['nom']) ?></td>
                    <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
                    <td>
                        <a href="index.php?page=categories&action=edit&id=<?= $cat['id'] ?>">
                            ✏️ Modifier
                        </a>
                        <a href="index.php?page=categories&action=delete&id=<?= $cat['id'] ?>"
                           onclick="return confirm('Supprimer cette catégorie ?')">
                            🗑️ Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>