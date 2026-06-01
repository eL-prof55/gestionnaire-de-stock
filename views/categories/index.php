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
    <h1><i class="icon-folder"></i> Gestion des Catégories</h1>

    <div class="card">
        <div class="card-header">
            <h2>Ajouter une catégorie</h2>
        </div>
        <form id="form-categorie" class="form-inline">
            <div class="form-group">
                <input type="text" name="nom" placeholder="Nom de la catégorie *" required>
            </div>
            <div class="form-group">
                <input type="text" name="description" placeholder="Description (optionnel)">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="icon-plus"></i> Ajouter
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Liste des catégories</h2>
        </div>
        <table class="table" id="table-categories">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Articles liés</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr data-id="<?= $cat['id'] ?>">
                    <td><?= $cat['id'] ?></td>
                    <td class="cat-nom"><?= htmlspecialchars($cat['nom']) ?></td>
                    <td class="cat-desc"><?= htmlspecialchars($cat['description'] ?? '') ?></td>
                    <td>
                        <span class="badge badge-info">
                            <?= $controller->compterArticles($cat['id']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($cat['created_at'])) ?></td>
                    <td class="actions">
                        <button class="btn btn-sm btn-warning" onclick="editCategorie(<?= $cat['id'] ?>)">
                            <i class="icon-edit"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCategorie(<?= $cat['id'] ?>)">
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
