<?php
require_once 'models/user.php';
require_once 'config/db.php';

class authController{

    private $userModel;

     //creer linstance du model user

     public function __construct(){
        global $pdo;
        $this->userModel=new User ($pdo);
     }

     //affiche la page d'inscription, si un superadmin existe deja  -> login

    public function afficherInscription(){
        if ($this->userModel->superAdminExiste()){
            header('Location: ' .BASE_URL . 'login');
            exit();
        }
        require_once __DIR__ . 'views/auth/register.php';
    }

    //traite le formulaire d'inscription

    public function Inscrire(){
        //verifier que tout les formulaire sont remplis
        if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['mot_de_passe'])){
            $_SESSION['erreur'] = "Tous les champs sont obligatoires mon petit abec.";
            header('Location: ' . BASE_URL . 'register');
            exit();
        }

        //verifier le format de l'email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $_SESSION['erreur'] = "Email invalide mon petit abec.";
            header('Location: ' . BASE_URL . 'register');
            exit();
        }
    
        //verifier la longeur du mot de passe
        if (strlen($_POST['mot_de_passe']) < 6){
            $_SESSION['erreur'] = "Le mot de passe doit contenir au moins 6 caractères mon petit abec.";
            header('Location: ' . BASE_URL . 'register');
            exit();
        }

        //verifier que le superadmin n'existe pas deja
        if ($this->userModel->superAdminExiste()){
            header('Location: ' . BASE_URL . 'register');
            exit();
        }

        //verifier que l'email n'est pas deja utilise
        if ($this->userModel->emailExiste($_POST['email'])){
            $_SESSION['erreur'] = "Email déjà utilisé mon petit abec.";
            header('Location: ' . BASE_URL . 'register');
            exit();
        }
        
        //creer le compte superadmin
        $succes = $this->userModel->creerCompte(
            trim($_POST['nom']),
            trim($_POST['email']),
            $_POST['mot_de_passe'],
            'superadmin'
        );

        if ($succes){
            $_SESSION['success'] = "Compte superadmin créé avec succès. Connectez vous !";
            header('Location: ' . BASE_URL . 'login');
            exit();
        } else {
            $_SESSION['erreur '] = "Une erreur est survenue, reesayer.";
            header('Location: ' . BASE_URL . 'register');
            exit();
        }
    }

    //affichage de la page de login, si deja connecte -> dashboard
    public function afficherConnexion(){
        if (isset($_SESSION['user_id'])){
            header('Location: ' . BASE_URL . 'dashboard');
            exit();
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    //traite le formulaire de login

    public function connecter(){
        //verifier que tout les champs sont remplis
        if (empty($_POST['email']) || empty($_POST['mot_de_passe'])){
            $_SESSION['erreur'] = "Tous les champs sont obligatoires mon petit abec.";
            header('Location: ' . BASE_URL . 'login');
            exit();
        }

        //trouver l'utilisateur par email
        $user = $this->userModel->trouverParEmail($_POST['email']);

        // verifeir aue l'utilisateur existe et que le mot de passe est correct
        if (!$user || !password_verify($_POST['mot_de_passe'], $user['mot_de_passe'])){
            $_SESSION['erreur'] = "Email ou mot de passe incorrect.";
            header('Location: ' . BASE_URL . 'login');
            exit();
        }

        //creer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['role'] = $user['role'];

        //rediriger vers le dashboard
        header('Location: ' . BASE_URL . 'dashboard');
            exit();
    }

    //deconnexion , detruit la session
    public function deconnecter(){
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit();
    }

    //verifier si l'utilsateur est connecter ,redirige vers le login si pas connecter, a appeler en haut de chaque page qui necessite une authentification
    public static function verifierConnexion(){
        if (!isset($_SESSION['user_id'])){
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }

    //verifier si l'utilisateur est superadmin, redirige vers le dashboard si pas superadmin, a appeler en haut de chaque page qui necessite une autorisation de superadmin
    public static function verifierSuperAdmin(){
        self::verifierConnexion();
        if ($_SESSION['role'] !== 'superadmin'){
            header('Location: ' . BASE_URL . 'dashboard');
            exit();
        }   
    }
}    