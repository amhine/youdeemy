<?php

require_once("./app/libraries/database.php");

class UserDAO {

    private $connect;

    public function __construct() {
        $this->connect = (new Connexion())->getConnection();
    }

    public function signup($nom, $email, $password, $role, $status) {
        $email_query = $this->connect->prepare("SELECT id_user FROM utilisateur WHERE email = ?");
        $email_query->execute([$email]);
        
        if ($email_query->rowCount() > 0) {
            return "Cet email existe déjà";
        }
    
        if (strlen($password) < 6) {
            return "Le mot de passe doit contenir au moins 6 caractères";
        }
    
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $date_creation = date('Y-m-d H:i:s');
    
        $role_query = $this->connect->prepare("SELECT id_role FROM role WHERE nom_role = ?");
        $role_query->execute([$role]);
        
        if ($role_query->rowCount() == 0) {
            return "Rôle non valide";
        }
    
        $role_id = $role_query->fetch(PDO::FETCH_OBJ)['id_role'];
    
        if ($role === 'Enseignant') {
            $status = 'inactif';
        }
    
        try {
            $sql = "INSERT INTO utilisateur (nom_user, email, password, id_role, date_creation, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$nom, $email, $password_hash, $role_id, $date_creation, $status]);
            return "Inscription réussie";
        } catch(PDOException $e) {
            return "Erreur lors de l'inscription: " . $e->getMessage();
        }
    }
    
    public function signin($email, $password) {
        try {
            $sql = "SELECT * FROM utilisateur WHERE email = '$email'";
            $result = $this->connect->query($sql);

            if ($row = $result->fetch(PDO::FETCH_OBJ)) {
                if (password_verify($password, $row['password'])) {
                    session_start();
                    $_SESSION['id_user'] = $row['id_user'];
                    $_SESSION['id_role'] = $row['id_role'];
                    $_SESSION['nom_user'] = $row['nom_user'];
                    return "Connexion réussie";
                } else {
                    return "Mot de passe incorrect";
                }
            } else {
                return "Email non trouvé";
            }
        } catch(PDOException $e) {
            return "Erreur lors de la connexion: " . $e->getMessage();
        }
    }
}

?>