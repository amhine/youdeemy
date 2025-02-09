<?php 

require_once("./app/libraries/database.php");
require_once './DAO/categoriedao.php'; 

class CategorieController {
    private $categorieDAO;

    public function __construct() {
        $this->categorieDAO = new CategorieDAO();
    }
    public function index() {
        if (!isset($_SESSION['id_user'])) {
            header("Location: index.php?action=signin");
            exit;
        }
        
        if ($_SESSION['id_role'] == '2') {  
            if ($_SERVER['REQUEST_URI'] !== '/categorie') {
                header("Location: /categorie");
                exit;
            }
        }
    
        $categories = $this->categorieDAO->getCategories();
        require_once 'app/views/etudient/categorie.php';
    }
    
    public function encategorie() {
        if (!isset($_SESSION['id_user'])) {
            header("Location: index.php?action=signin");
            exit;
        }
    
        if ($_SESSION['id_role'] == '3') {  
            if ($_SERVER['REQUEST_URI'] !== '/encategorie') {
                header("Location: /encategorie"); 
                exit;
            }
        }
    
        $categories = $this->categorieDAO->getCategories();
        require_once 'app/views/enseignent/categorie.php'; 
    }
    

    public function showAjoutForm() {
        require_once  './app/views/enseignent/ajoutercategorie.php';
    }

    public function ajouterNouvelleCategorie() {
        if (!isset($_SESSION['id_user'])) {
            header("Location: index.php?action=signin");
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
    
            if (empty($nom) || empty($description)) {
               header("Location: /ajoutercat?error=1");
                exit;
            }
    
            
            $categorie = new Categorie($nom, $description);
    
            $categorieDAO = new CategorieDAO();
            $id = $categorieDAO->ajouterCategorie($categorie);
    
            header("Location: /encategorie");
            exit;
        } else {
            header("Location: /ajoutercat");
            exit;
        }
    }
    

    public function supprimerCategorie($id) {

        if (!isset($_SESSION['id_user'])) {
            header("Location: index.php?action=signin");
            exit;
        }
        $success = $this->categorieDAO->supprimerCategorie($id);
        if ($success) {
            header("Location: /encategorie");  
            exit;
        } else {
            
            header("Location: /encategorie"); 
            exit;
        }
        
    }
    public function showModifForm($id_categorie) {
        if (!isset($_SESSION['id_user'])) {
            header("Location: /signin");
            exit;
        }
        $categorie = $this->categorieDAO->getCategorieById($id_categorie);
    
        if (!$categorie) {
            $_SESSION['error'] = "Catégorie non trouvée.";
            header("Location: /encategorie");
            exit;
        }
        require_once './app/views/enseignent/modifiercategorie.php';
    }
    

    
    
        
        
    } 


?>
