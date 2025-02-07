<?php 

require_once("./app/libraries/database.php");
require_once './DAO/categoriedao.php'; 

class CategorieController {
    private $categorieDAO;

    public function __construct() {
        $this->categorieDAO = new CategorieDAO();
    }
    public function index() {
        // if (!isset($_SESSION['id_user'])) {
        //     header("Location: index.php?action=signin");
        //     exit;
        // }

        $categories = $this->categorieDAO->getCategories();
        require_once 'app/views/enseignent/categorie.php';
    }

    public function details($id) {
        // if (!isset($_SESSION['id_user'])) {
        //     header("Location: index.php?action=signin");
        //     exit;
        // }

        $categorie = $this->categorieDAO->getCategorieById($id);
        require_once 'app/views/enseignent/detailcategorie.php';
    }

    public function ajouterNouvelleCategorie($nom, $description) {
        try {
            $categorie = new Categorie($nom, $description);

            $categorieDAO = new CategorieDAO();
            $id = $categorieDAO->ajouterCategorie($categorie);

            echo "Catégorie ajoutée avec succès, ID : " . $id;

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function supprimerCategorie($id) {
        try {
            $categorieDAO = new CategorieDAO();
            $categorieDAO->supprimerCategorie($id);

            echo "Catégorie supprimée avec succès.";

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}

?>
