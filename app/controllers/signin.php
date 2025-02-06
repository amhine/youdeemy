<?php


require_once("./app/libraries/database.php");
require_once './../models/user.php';
require_once './DAO/user.php';
class ConnexionController {

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];  
            $userDAO = new UserDAO();
            $resultat = $userDAO->signin($email, $password);

            if ($resultat == "Connexion réussie") {
                if ($_SESSION['id_role'] == 1) {
                    header("Location: ./../admin/dashbord.php");
                } elseif ($_SESSION['id_role'] == 2) {
                    header("Location: ./../etudient/home.php");
                } else {
                    header("Location: ./../enseignent/home.php");
                }
                exit();
            } else {
                header("Location: ./views/signin.php?error=" . urlencode($resultat));
                exit();
            }
        }
    }
   
}

?>
