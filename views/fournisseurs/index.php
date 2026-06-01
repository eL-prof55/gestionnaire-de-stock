<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fournisseurs</title>
    <link rel="stylesheet" href="/gestionnaire-de-stock-main/assets/css/style.css">
</head>
<body>
    <?php include_once __DIR__ . '/../layout/header.php'; ?>
    
    <div class="container">
        <h1>📦 Gestion des Fournisseurs</h1>
        
        <div class="card">
            <h2>➕ Ajouter un fournisseur</h2>
            <form id="formFournisseur">
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" id="nom" required>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="telephone" id="telephone">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea name="adresse" id="adresse" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
        
        <div class="card">
            <h2>📋 Liste des fournisseurs</h2>
            <table id="tableFournisseurs">
                <thead>
                    <tr><th>ID</th><th>Nom</th><th>Téléphone</th><th>Email</th><th>Adresse</th><th>Articles liés</th><th>Actions</th></tr>
                </thead>
                <tbody id="fournisseursList">
                    <?php foreach($fournisseurs as $f): ?>
                    <tr id="fournisseur-<?= $f['id'] ?>">
                        <td><?= $f['id'] ?></td>
                        <td class="edit-nom"><?= htmlspecialchars($f['nom']) ?></td>
                        <td class="edit-telephone"><?= htmlspecialchars($f['telephone']) ?></td>
                        <td class="edit-email"><?= htmlspecialchars($f['email']) ?></td>
                        <td class="edit-adresse"><?= htmlspecialchars($f['adresse']) ?></td>
                        <td><?= $f['nb_articles'] ?></td>
                        <td>
                            <button class="btn-edit" data-id="<?= $f['id'] ?>">✏️ Modifier</button>
                            <button class="btn-delete" data-id="<?= $f['id'] ?>">🗑️ Supprimer</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifier le fournisseur</h2>
            <form id="formEditFournisseur">
                <input type="hidden" id="edit_id">
                <div class="form-group"><label>Nom *</label><input type="text" id="edit_nom" required></div>
                <div class="form-group"><label>Téléphone</label><input type="tel" id="edit_telephone"></div>
                <div class="form-group"><label>Email</label><input type="email" id="edit_email"></div>
                <div class="form-group"><label>Adresse</label><textarea id="edit_adresse" rows="3"></textarea></div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
    
    <style>
        .card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background-color: #2E75B6; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .btn-edit, .btn-delete { margin: 0 5px; padding: 5px 10px; cursor: pointer; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: white; margin: 10% auto; padding: 20px; width: 50%; border-radius: 10px; }
        .close { float: right; font-size: 28px; cursor: pointer; }
    </style>
    
    <script>
        document.getElementById('formFournisseur').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('nom', document.getElementById('nom').value);
            formData.append('telephone', document.getElementById('telephone').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('adresse', document.getElementById('adresse').value);
            const response = await fetch('/gestionnaire-de-stock-main/controllers/fournisseurcontroller.php?action=create', { method: 'POST', body: formData });
            const result = await response.json();
            if(result.success) { alert('✅ ' + result.message); location.reload(); }
            else { alert('❌ ' + result.message); }
        });
        
        const modal = document.getElementById('editModal');
        const closeBtn = document.querySelector('.close');
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const row = document.getElementById(`fournisseur-${id}`);
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nom').value = row.querySelector('.edit-nom').textContent;
                document.getElementById('edit_telephone').value = row.querySelector('.edit-telephone').textContent;
                document.getElementById('edit_email').value = row.querySelector('.edit-email').textContent;
                document.getElementById('edit_adresse').value = row.querySelector('.edit-adresse').textContent;
                modal.style.display = 'block';
            });
        });
        closeBtn.onclick = () => modal.style.display = 'none';
        window.onclick = (e) => { if(e.target == modal) modal.style.display = 'none'; };
        
        document.getElementById('formEditFournisseur').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('id', document.getElementById('edit_id').value);
            formData.append('nom', document.getElementById('edit_nom').value);
            formData.append('telephone', document.getElementById('edit_telephone').value);
            formData.append('email', document.getElementById('edit_email').value);
            formData.append('adresse', document.getElementById('edit_adresse').value);
            const response = await fetch('/gestionnaire-de-stock-main/controllers/fournisseurcontroller.php?action=update', { method: 'POST', body: formData });
            const result = await response.json();
            if(result.success) { alert('✅ ' + result.message); location.reload(); }
            else { alert('❌ ' + result.message); }
        });
        
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async () => {
                if(!confirm('⚠️ Supprimer ce fournisseur ?')) return;
                const formData = new FormData();
                formData.append('id', btn.dataset.id);
                const response = await fetch('/gestionnaire-de-stock-main/controllers/fournisseurcontroller.php?action=delete', { method: 'POST', body: formData });
                const result = await response.json();
                if(result.success) { alert('✅ ' + result.message); location.reload(); }
                else { alert('❌ ' + result.message); }
            });
        });
    </script>
</body>
</html>