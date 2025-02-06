<?php 

require_once("./app/libraries/database.php");
require_once './DAO/categoriedao.php'; 

class CategorieController {

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
