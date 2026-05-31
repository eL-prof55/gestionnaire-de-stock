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
        <h1><i class="icon-box"></i> Catalogue des Articles</h1>
        <a href="index.php?page=articles&action=create" class="btn btn-primary">
            <i class="icon-plus"></i> Nouvel Article
        </a>
    </div>

    <div class="card">
        <table class="table table-articles" id="table-articles">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Fournisseur</th>
                    <th>Prix (FCFA)</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $art):
                    $alerte = $art['quantite'] <= $art['seuil_min'];
                    $warning = !$alerte && $art['quantite'] <= $art['seuil_min'] * 2;
                    $statutClass = $alerte ? 'badge-danger' : ($warning ? 'badge-warning' : 'badge-success');
                    $statutText = $alerte ? 'Alerte' : ($warning ? 'Attention' : 'Normal');
                ?>
                <tr>
                    <td class="cell-image">
                        <?php if ($art['image']): ?>
                            <img src="assets/uploads/articles/<?= htmlspecialchars($art['image']) ?>"
                                 alt="<?= htmlspecialchars($art['nom']) ?>" class="thumb">
                        <?php else: ?>
                            <div class="no-image"><i class="icon-image"></i></div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($art['nom']) ?></strong></td>
                    <td>
                        <?php if ($art['nom_categorie']): ?>
                            <span class="badge badge-info"><?= htmlspecialchars($art['nom_categorie']) ?></span>
                        <?php else: ?>
                            <em>Non classé</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $art['nom_fournisseur'] ? htmlspecialchars($art['nom_fournisseur']) : '<em>Non défini</em>' ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($art['prix_unitaire'], 0, ',', ' ') ?> FCFA
                    </td>
                    <td class="text-center"><strong><?= $art['quantite'] ?></strong></td>
                    <td>
                        <span class="badge <?= $statutClass ?> <?= $alerte ? 'pulse-alerte' : '' ?>">
                            <?= $statutText ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="index.php?page=articles&action=edit&id=<?= $art['id'] ?>"
                           class="btn btn-sm btn-warning">
                            <i class="icon-edit"></i> Modifier
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteArticle(<?= $art['id'] ?>)">
                            <i class="icon-trash"></i> Supprimer
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
