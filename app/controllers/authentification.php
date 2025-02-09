<?php

require_once("./app/libraries/database.php");
require_once './app/models/user.php';
require_once './DAO/user.php';
require_once './DAO/role.php';


class AuthController {
    private $connect;

    public function __construct() {
        $this->connect = new Connexion();
    }
    public function showRegistrationForm() {
        $roleDAO = new RoleDAO();
        $roles = $roleDAO->getRole();
        
        require_once  './app/views/signup.php';
    }

   

    public function signup() {
        try {
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                $_SESSION['error'] = "Aucune donnée reçue";
                header('Location: /register');
                exit();
            }
        
            $requiredFields = ['nom_user', 'email', 'password', 'role'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                    $_SESSION['error'] = "Le champ {$field} est obligatoire";
                    header('Location: /register');
                    exit();
                }
            }
        
            $nom = trim($_POST['nom_user']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = trim($_POST['role']);
        
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Format d'email invalide";
                header('Location: /register');
                exit();
            }
        
            if (strlen($password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères";
                header('Location: /register');
                exit();
            }
        
            $conn = $this->connect->getConnection();
            
            $email_query = $conn->prepare("SELECT id_user FROM utilisateur WHERE email = ?");
            $email_query->execute([$email]);
            if ($email_query->rowCount() > 0) {
                $_SESSION['error'] = "Cet email existe déjà";
                header('Location: /register');
                exit();
            }
    
            $role_query = $conn->prepare("SELECT id_role FROM role WHERE nom_role = ?");
            $role_query->execute([$role]);
            if ($role_query->rowCount() == 0) {
                $_SESSION['error'] = "Rôle non valide";
                header('Location: /register');
                exit();
            }
    
            $role_obj = $role_query->fetch(PDO::FETCH_OBJ);
            $role_id = $role_obj->id_role;
    
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $date_creation = date('Y-m-d H:i:s');
            $status = ($role === 'enseignent') ? 'inactif' : 'actif';
    
            $stmt = $conn->prepare("
                INSERT INTO utilisateur (nom_user, email, password, id_role, date_creation, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([$nom, $email, $password_hash, $role_id, $date_creation, $status]);
            
            if ($result) {
                $_SESSION['success'] = "Inscription réussie";
                header('Location: /login');
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de l'inscription";
                header('Location: /register');
                exit();
            }
    
        } catch(PDOException $e) {
            error_log("Erreur d'inscription : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
            header('Location: /register');
            exit();
        }
    }
    public function showLoginForm() {
        require_once 'app/views/signin.php';
    }
   
    
    public function signin() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $_SESSION['error'] = "Aucune donnée reçue";
            header('Location: /login');
            exit();
        }
    
        if (!isset($_POST['email'], $_POST['password'])) {
            return "Email et mot de passe requis";
        }
    
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
    
        if (!$email) {
            return "Format d'email invalide";
        }
    
        try {
            $conn = $this->connect->getConnection();
            $query = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $query->execute([$email]);
    
            if ($query->rowCount() == 0) {
                return "Email ou mot de passe incorrect";
            }
    
            $user = $query->fetch(PDO::FETCH_OBJ);
    
            if (!password_verify($password, $user->password)) {
                return "Email ou mot de passe incorrect";
            }
    
            if ($user->status === 'inactif') {
                $_SESSION['message'] = "Votre compte est inactif, veuillez patienter.";
                header('Location: /attendre');
                exit();
            }
    
            $_SESSION['id_user'] = $user->id_user;
            $_SESSION['nom_user'] = $user->nom_user;
            $_SESSION['id_role'] = $user->id_role;
    
            switch ($user->id_role) {
                case 1:
                    header('Location: /dashbord');
                    break;
                case 2:
                    require_once  './app/views/etudient/home.php';
                    break;
                case 3:
                    require_once  './app/views/enseignent/home.php';
                    break;
                default:
                    header('Location: /login');
                    break;
            }
            exit();
    
        } catch(PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            return "Une erreur est survenue lors de la connexion";
        }
    }
    
    public function attendre(){
        require_once  './app/views/enseignent/attendre.php';
    }
    
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit();
    }
    
    
    
}
?>
