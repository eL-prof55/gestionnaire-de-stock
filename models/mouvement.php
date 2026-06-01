<?php
require_once __DIR__ . '/../config/db.php';

class Mouvement {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnexion();
    }
    
    // Enregistrer un mouvement et mettre à jour le stock
    public function addMouvement($id_article, $type, $quantite, $motif, $id_user) {
        try {
            $this->pdo->beginTransaction();
            
            // 1. Récupérer la quantité actuelle
            $stmt = $this->pdo->prepare("SELECT quantite FROM articles WHERE id = ?");
            $stmt->execute([$id_article]);
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!$article) {
                throw new Exception("Article non trouvé");
            }
            
            $nouvelle_quantite = $article['quantite'];
            
            // 2. Calculer la nouvelle quantité
            if($type == 'entree') {
                $nouvelle_quantite += $quantite;
            } else if($type == 'sortie') {
                if($quantite > $article['quantite']) {
                    throw new Exception("Stock insuffisant ! Stock actuel : " . $article['quantite']);
                }
                $nouvelle_quantite -= $quantite;
            }
            
            // 3. Mettre à jour la quantité de l'article
            $stmt = $this->pdo->prepare("UPDATE articles SET quantite = ? WHERE id = ?");
            $stmt->execute([$nouvelle_quantite, $id_article]);
            
            // 4. Enregistrer le mouvement
            $stmt = $this->pdo->prepare("
                INSERT INTO mouvements (id_article, type, quantite, motif, id_user) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id_article, $type, $quantite, $motif, $id_user]);
            
            $this->pdo->commit();
            
            return [
                'success' => true, 
                'nouvelle_quantite' => $nouvelle_quantite,
                'message' => ucfirst($type) . ' enregistrée avec succès'
            ];
            
        } catch(Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Récupérer l'historique des mouvements
    public function getHistorique($filtre_article = null, $filtre_type = null, $filtre_date = null) {
        $sql = "
            SELECT m.*, a.nom as article_nom, u.nom as user_nom 
            FROM mouvements m
            JOIN articles a ON m.id_article = a.id
            JOIN users u ON m.id_user = u.id
            WHERE 1=1
        ";
        $params = [];
        
        if($filtre_article) {
            $sql .= " AND m.id_article = ?";
            $params[] = $filtre_article;
        }
        if($filtre_type) {
            $sql .= " AND m.type = ?";
            $params[] = $filtre_type;
        }
        if($filtre_date) {
            $sql .= " AND DATE(m.created_at) = ?";
            $params[] = $filtre_date;
        }
        
        $sql .= " ORDER BY m.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les mouvements d'un article spécifique
    public function getByArticle($id_article) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.nom as user_nom 
            FROM mouvements m
            JOIN users u ON m.id_user = u.id
            WHERE m.id_article = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$id_article]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>