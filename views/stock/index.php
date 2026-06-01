<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock</title>
    <link rel="stylesheet" href="/gestionnaire-de-stock/assets/css/style.css">
</head>
<body>
    <?php include_once __DIR__ . '/../layout/header.php'; ?>
    
    <div class="container">
        <h1>📊 Gestion du Stock</h1>
        
        <!-- Formulaire mouvement -->
        <div class="card">
            <h2>➕ Enregistrer un mouvement</h2>
            <form id="formMouvement">
                <div class="form-group">
                    <label>Article *</label>
                    <select id="id_article" required>
                        <option value="">Sélectionner un article</option>
                        <?php foreach($articles as $a): ?>
                        <option value="<?= $a['id'] ?>">
                            <?= htmlspecialchars($a['nom']) ?> (Stock: <?= $a['quantite'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Type de mouvement *</label>
                    <select id="type" required>
                        <option value="entree">📥 Entrée (réapprovisionnement)</option>
                        <option value="sortie">📤 Sortie (vente/utilisation)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Quantité *</label>
                    <input type="number" id="quantite" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Motif *</label>
                    <input type="text" id="motif" placeholder="Ex: Vente client, Réapprovisionnement fournisseur..." required>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer le mouvement</button>
            </form>
        </div>
        
        <!-- Historique des mouvements -->
        <div class="card">
            <h2>📜 Historique des mouvements</h2>
            
            <!-- Filtres -->
            <div class="filters">
                <input type="text" id="filtreArticle" placeholder="Filtrer par article...">
                <select id="filtreType">
                    <option value="">Tous les types</option>
                    <option value="entree">Entrées</option>
                    <option value="sortie">Sorties</option>
                </select>
                <input type="date" id="filtreDate">
                <button id="btnFiltrer" class="btn btn-secondary">Filtrer</button>
            </div>
            
            <table id="tableHistorique">
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
                <tbody id="historiqueList">
                    <?php foreach($historique as $h): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></td>
                        <td><?= htmlspecialchars($h['article_nom']) ?></td>
                        <td>
                            <?php if($h['type'] == 'entree'): ?>
                                <span style="color: green;">📥 Entrée</span>
                            <?php else: ?>
                                <span style="color: red;">📤 Sortie</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $h['quantite'] ?></td>
                        <td><?= htmlspecialchars($h['motif']) ?></td>
                        <td><?= htmlspecialchars($h['user_nom']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Enregistrer un mouvement
        document.getElementById('formMouvement').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('id_article', document.getElementById('id_article').value);
            formData.append('type', document.getElementById('type').value);
            formData.append('quantite', document.getElementById('quantite').value);
            formData.append('motif', document.getElementById('motif').value);
            
            const response = await fetch('/gestionnaire-de-stock/controllers/MouvementController.php?action=add', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if(result.success) {
                alert('✅ ' + result.message);
                location.reload(); // Recharge pour voir le nouveau stock
            } else {
                alert('❌ ' + result.message);
            }
        });
        
        // Filtrer l'historique
        document.getElementById('btnFiltrer').addEventListener('click', async () => {
            const article = document.getElementById('filtreArticle').value;
            const type = document.getElementById('filtreType').value;
            const date = document.getElementById('filtreDate').value;
            
            let url = '/gestionnaire-de-stock/controllers/MouvementController.php?action=getHistorique';
            if(article) url += `&article=${article}`;
            if(type) url += `&type=${type}`;
            if(date) url += `&date=${date}`;
            
            const response = await fetch(url);
            const result = await response.json();
            
            if(result.success) {
                const tbody = document.getElementById('historiqueList');
                tbody.innerHTML = '';
                
                result.data.forEach(m => {
                    const row = tbody.insertRow();
                    row.innerHTML = `
                        <td>${new Date(m.created_at).toLocaleString()}</td>
                        <td>${m.article_nom}</td>
                        <td>${m.type === 'entree' ? '📥 Entrée' : '📤 Sortie'}</td>
                        <td>${m.quantite}</td>
                        <td>${m.motif}</td>
                        <td>${m.user_nom}</td>
                    `;
                });
            }
        });
    </script>
    
    <style>
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #2E75B6;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filters input, .filters select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</body>
</html>