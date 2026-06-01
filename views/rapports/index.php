<div class="container">
    <h2>📊 Rapports & Statistiques</h2>

    <!-- 1. STATISTIQUES GÉNÉRALES -->
    <div class="stats-cards">
        <div class="card">
            <h3>📦 Total articles</h3>
            <p class="chiffre"><?= $stats['total_articles'] ?></p>
        </div>
        <div class="card">
            <h3>💰 Valeur du stock</h3>
            <p class="chiffre"><?= number_format($stats['valeur_stock'], 0, ',', ' ') ?> FCFA</p>
        </div>
        <div class="card alerte">
            <h3>⚠️ Articles en alerte</h3>
            <p class="chiffre"><?= $stats['articles_en_alerte'] ?></p>
        </div>
        <div class="card">
            <h3>🔄 Mouvements du mois</h3>
            <p class="chiffre"><?= $stats['mouvements_mois'] ?></p>
        </div>
    </div>

    <!-- 2. ARTICLES EN ALERTE -->
    <h3>⚠️ Articles en alerte</h3>
    <?php if (empty($alertes)): ?>
        <p>✅ Aucun article en alerte.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Quantité actuelle</th>
                    <th>Seuil minimum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alertes as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= htmlspecialchars($a['categorie'] ?? 'Aucune') ?></td>
                    <td style="color:red"><?= $a['quantite'] ?></td>
                    <td><?= $a['seuil_min'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- 3. HISTORIQUE DES MOUVEMENTS -->
    <h3>🔄 Historique des mouvements</h3>

    <!-- Filtres -->
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="rapports">
        <select name="type">
            <option value="">-- Tous les types --</option>
            <option value="entree" <?= ($_GET['type'] ?? '') === 'entree' ? 'selected' : '' ?>>Entrée</option>
            <option value="sortie" <?= ($_GET['type'] ?? '') === 'sortie' ? 'selected' : '' ?>>Sortie</option>
        </select>
        <input type="date" name="date" value="<?= $_GET['date'] ?? '' ?>">
        <button type="submit">🔍 Filtrer</button>
        <a href="index.php?page=rapports">❌ Réinitialiser</a>
    </form>

    <?php if (empty($historique)): ?>
        <p>Aucun mouvement trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Article</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Motif</th>
                    <th>Utilisateur</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $m): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                    <td><?= htmlspecialchars($m['article_nom'] ?? '') ?></td>
                    <td>
                        <?php if ($m['type'] === 'entree'): ?>
                            <span style="color:green">⬆️ Entrée</span>
                        <?php else: ?>
                            <span style="color:red">⬇️ Sortie</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $m['quantite'] ?></td>
                    <td><?= htmlspecialchars($m['motif'] ?? '') ?></td>
                    <td><?= htmlspecialchars($m['user_nom'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- 4. ARTICLES LES PLUS MOUVEMENTÉS -->
    <h3>🏆 Articles les plus mouvementés</h3>
    <?php if (empty($plusMouvementes)): ?>
        <p>Aucun mouvement enregistré.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Total mouvements</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plusMouvementes as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['nom'] ?? '') ?></td>
                    <td><?= $a['total_mouvements'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- 5. STOCK PAR CATÉGORIE -->
    <h3>📂 Stock par catégorie</h3>
    <?php if (empty($parCategorie)): ?>
        <p>Aucune catégorie trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Nombre d'articles</th>
                    <th>Valeur totale</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parCategorie as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['categorie']) ?></td>
                    <td><?= $c['nombre_articles'] ?></td>
                    <td><?= number_format($c['valeur_totale'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- 6. EXPORT XML -->
    <h3>📤 Export / Import XML</h3>
    <a href="xml/export_stock.php">
        <button>📥 Exporter en XML</button>
    </a>

    <form method="POST" action="xml/import_articles.php" enctype="multipart/form-data">
        <input type="file" name="fichier_xml" accept=".xml" required>
        <button type="submit">📤 Importer XML</button>
    </form>
</div>