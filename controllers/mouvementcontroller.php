<?php
require_once __DIR__ . '/../models/Mouvement.php';
require_once __DIR__ . '/../models/Article.php';

class MouvementController {
    private $mouvementModel;
    private $articleModel;
    
    public function __construct() {
        $this->mouvementModel = new Mouvement();
        $this->articleModel = new Article();
    }
    
    // Afficher la page des mouvements
    public function index() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: /gestionnaire-de-stock/controllers/AuthController.php?action=login');
            exit();
        }
        
        $historique = $this->mouvementModel->getHistorique();
        $articles = $this->articleModel->getAll();
        
        require_once __DIR__ . '/../views/stock/index.php';
    }
    
    // Ajouter un mouvement (entrée ou sortie) via AJAX
    public function addMouvement() {
        header('Content-Type: application/json');
        session_start();
        
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Non authentifié']);
            return;
        }
        
        $id_article = $_POST['id_article'] ?? 0;
        $type = $_POST['type'] ?? '';
        $quantite = $_POST['quantite'] ?? 0;
        $motif = $_POST['motif'] ?? '';
        $id_user = $_SESSION['user_id'];
        
        // Validation
        if($id_article <= 0) {
            echo json_encode(['success' => false, 'message' => 'Article invalide']);
            return;
        }
        
        if(!in_array($type, ['entree', 'sortie'])) {
            echo json_encode(['success' => false, 'message' => 'Type de mouvement invalide']);
            return;
        }
        
        if($quantite <= 0) {
            echo json_encode(['success' => false, 'message' => 'La quantité doit être supérieure à 0']);
            return;
        }
        
        if(empty($motif)) {
            echo json_encode(['success' => false, 'message' => 'Le motif est obligatoire']);
            return;
        }
        
        $result = $this->mouvementModel->addMouvement($id_article, $type, $quantite, $motif, $id_user);
        echo json_encode($result);
    }
    
    // Récupérer l'historique filtré via AJAX
    public function getHistorique() {
        header('Content-Type: application/json');
        
        $filtre_article = $_GET['article'] ?? null;
        $filtre_type = $_GET['type'] ?? null;
        $filtre_date = $_GET['date'] ?? null;
        
        $historique = $this->mouvementModel->getHistorique($filtre_article, $filtre_type, $filtre_date);
        echo json_encode(['success' => true, 'data' => $historique]);
    }
}

// Routage
if(isset($_GET['action'])) {
    $controller = new MouvementController();
    
    switch($_GET['action']) {
        case 'add':
            $controller->addMouvement();
            break;
        case 'getHistorique':
            $controller->getHistorique();
            break;
        default:
            $controller->index();
    }
} else {
    $controller = new MouvementController();
    $controller->index();
}
?>