<?php
require_once("./app/libraries/database.php");
require_once './app/models/user.php';
require_once './DAO/user.php';
require_once './DAO/role.php';

class HomeController {
    public function index() {
        if (!isset($_SESSION['id_user'])) {
            header('Location: /login');
            exit();
        }
        $userName = htmlspecialchars($_SESSION['nom_user'] ?? 'Utilisateur'); 
        $role = $_SESSION['id_role'] ?? null;

        require_once './app/views/etudient/home.php';
    }
}

