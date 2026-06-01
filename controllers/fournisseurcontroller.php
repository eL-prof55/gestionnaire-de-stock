<?php
require_once __DIR__ . '/../models/Fournisseur.php';

class FournisseurController {
    private $fournisseurModel;
    
    public function __construct() {
        $this->fournisseurModel = new Fournisseur();
    }
    
    public function index() {
        $fournisseurs = $this->fournisseurModel->getAll();
        foreach($fournisseurs as &$f) {
            $f['nb_articles'] = $this->fournisseurModel->countArticles($f['id']);
        }
        require_once __DIR__ . '/../views/fournisseurs/index.php';
    }
    
    public function create() {
        header('Content-Type: application/json');
        $nom = $_POST['nom'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $email = $_POST['email'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        
        if(empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
            return;
        }
        if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide']);
            return;
        }
        if($this->fournisseurModel->create($nom, $telephone, $email, $adresse)) {
            echo json_encode(['success' => true, 'message' => 'Fournisseur ajouté']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur']);
        }
    }
    
    public function update() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? 0;
        $nom = $_POST['nom'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $email = $_POST['email'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        
        if(empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
            return;
        }
        if($this->fournisseurModel->update($id, $nom, $telephone, $email, $adresse)) {
            echo json_encode(['success' => true, 'message' => 'Fournisseur modifié']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur']);
        }
    }
    
    public function delete() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? 0;
        if($this->fournisseurModel->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Fournisseur supprimé']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur']);
        }
    }
    
    public function getAll() {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $this->fournisseurModel->getAll()]);
    }
}

if(isset($_GET['action'])) {
    $c = new FournisseurController();
    switch($_GET['action']) {
        case 'getAll': $c->getAll(); break;
        case 'create': $c->create(); break;
        case 'update': $c->update(); break;
        case 'delete': $c->delete(); break;
        default: $c->index();
    }
} else {
    $c = new FournisseurController();
    $c->index();
}
?>