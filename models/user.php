<?php
class user{
    private $pdo;

    // construteur, sa recoit la connexion pdo depuis le controller

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    // verifie si un superadmin existe deja en BDD et retourne true ou false

    public function superAdminExiste(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'superadmin'");
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    //creer un nouveau compte(superadmin ou gestionnaire) en BDD, retourne true si l'operation a reussi et false sinon

    public function creerCompte($nom,$email, $password, $role){
        // verifier que le role est valide
        //if(!in_array($role, ['superadmin', 'gestionnaire'])){
        //    return false;
        //}
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mot_de_passe, :role)");
        return $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':mot_de_passe' => $hash,
            ':role' => $role
        ]);
    }
    public function trouverParEmail($email){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email AND actif = 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

}