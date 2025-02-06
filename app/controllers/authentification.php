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
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            return "Aucune donnée reçue";
        }
    
        $requiredFields = ['nom_user', 'email', 'password', 'role'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                return "Le champ {$field} est obligatoire";
            }
        }
    
        $nom = $_POST['nom_user'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
    
        if (!$email) {
            return "Format d'email invalide";
        }
    
        if (strlen($password) < 6) {
            return "Le mot de passe doit contenir au moins 6 caractères";
        }
    
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $nom)) {
            return "Le nom contient des caractères non autorisés";
        }
    
        try {
            $conn = $this->connect->getConnection();
            
            $email_query = $conn->prepare("SELECT id_user FROM utilisateur WHERE email = ?");
            $email_query->execute([$email]);
            if ($email_query->rowCount() > 0) {
                return "Cet email existe déjà";
            }
    
            $role_query = $conn->prepare("SELECT id_role FROM role WHERE nom_role = ?");
            $role_query->execute([$role]);
            if ($role_query->rowCount() == 0) {
                return "Rôle non valide";
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
            
            $stmt->execute([$nom, $email, $password_hash, $role_id, $date_creation, $status]);
            
            if ($stmt->rowCount() > 0) {
                header('Location: signin.php');
                exit();
            } else {
                return "Erreur lors de l'inscription";
            }
    
        } catch(PDOException $e) {
            error_log("Erreur d'inscription : " . $e->getMessage());
            return "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
        }
    }
    public function showLoginForm() {
        require_once 'app/views/signin.php';
    }
    
    public function signin() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            return "Aucune donnée reçue";
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
                return "Votre compte n'a pas encore été activé";
            }
   
            session_start();
            $_SESSION['id_user'] = $user->id_user;
            $_SESSION['nom_user'] = $user->nom_user;
            $_SESSION['id_role'] = $user->id_role;
   
            switch ($user->id_role) {
                case 1:
                    header('Location: admin_dashboard.php');
                    break;
                case 2:
                    header('Location: student_dashboard.php');
                    break;
                case 3:
                    header('Location: teacher_dashboard.php');
                    break;
                default:
                    header('Location: index.php');
                    break;
            }
   
            exit();
   
        } catch(PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            return "Une erreur est survenue lors de la connexion";
        }
    }
   
    
    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit();
    }
    
    
    
}
?>
