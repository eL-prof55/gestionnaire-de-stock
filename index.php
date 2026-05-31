<?php

//demarrage de a session, premiere chose
session_start();

//chargement de la configuration
require_once __DIR__ . '/config/db.php';

//chargement des controllers
require_once __DIR__ . '/controllers/authcontroller.php';

//lecture de l'url, on recupere la page demader
$page = isset($_GET['page']) ? trim($_GET['page']) : '';

//routeur, selon la page demandee, on appelle le controller correspondant
$auth=new Authcontroller();
switch ($page){

//acceuil ->inscription ou login
    case '':
    case 'register':
        $auth->afficherInscription();
        break;

        //traitement de formulaire
    case 'inscrire':
       if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth->inscrire();
        } else {
            header('Location: ' . BASE_URL . 'register');
            exit();
        }
        break;

        //page de connexion
    case 'login':
        $auth->afficherConnexion();
        break;

        //traitement formulaire de connexion
    case 'connecter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth->connecter();
        } else {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
        break;

        //deconnexion
    case 'logout':
           $auth->deconnecter();
            break;  

            //dashboard
    case 'dashboard':
            AuthController::verifierConnexion();
            require_once __DIR__ . '/views/dashboard/index.php';
            break;

    //page introuvable
    default:
        http_response_code(404);
        echo "<h1>404- Page introuvable</h1>";
        echo "<a href='" . BASE_URL . "login'>Retour à la page de connexion</a>";
        break;
}