<?php

require_once(__DIR__ . "/../app/libraries/database.php");
require_once(__DIR__ . "/../app/Models/categorie.php");


class CategorieDAO {

    private $connect;

    public function __construct() {
        $this->connect = (new Connexion())->getConnection();
    }

  
    public function getCategories() {
        try {
            $sql = "SELECT * FROM categorie";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            $categories = [];
            
            while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
                $categories[] = new Categorie(
                    $obj->nom_categorie,
                    $obj->description,
                    $obj->id_categorie
                );
            }
            
            return $categories;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des catégories : " . $e->getMessage());
        }
    }


    public function ajouterCategorie(Categorie $categorie) {
        try {
            $sql = "INSERT INTO categorie (nom_categorie, description) VALUES (:nom_categorie, :description)";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':nom_categorie', $categorie->getNom());
            $stmt->bindParam(':description', $categorie->getDescription());
            $stmt->execute();

            return $this->connect->lastInsertId(); 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la catégorie : " . $e->getMessage());
        }
    }

    public function supprimerCategorie($id_categorie) {
        try {
            $sql = "DELETE FROM categorie WHERE id_categorie = :id_categorie";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();
 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de la catégorie : " . $e->getMessage());
        }
    }

    public function updateCategorie(Categorie $categorie) {
       
            $sql = "UPDATE categorie 
                    SET nom_categorie = :nom_categorie,
                        description = :description
                    WHERE id_categorie = :id_categorie";
    
            $stmt = $this->connect->prepare($sql);
    
            // Récupération des valeurs
            $id = $categorie->getIdCategorie();
            $nom = $categorie->getNom();
            $description = $categorie->getDescription();
    
            // Binding avec types PDO
            $stmt->bindParam(':id_categorie', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom_categorie', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    
            // Exécution de la requête
            $result = $stmt->execute();
    
            if (!$result) {
                error_log("Erreur SQL : " . print_r($stmt->errorInfo(), true));
                throw new Exception("Échec de la mise à jour de la catégorie");
            }
    
            return $result;
    
       
    }

    public function getCategorieById($id_categorie) {
        try {
            $sql = "SELECT * FROM categorie WHERE id_categorie = :id_categorie";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            if ($row) {
                $categorie = new Categorie($row->nom_categorie, $row->description);
                $categorie->setIdCategorie($row->id_categorie);
                return $categorie;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de la catégorie : " . $e->getMessage());
        }
    }
    public function CategorieById($id_categorie) {
        $query = "SELECT nom_categorie FROM categorie WHERE id_categorie = :id_categorie";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->nom_categorie : 'No Category';
    }
    public function getCategorieNom($id_categorie) {
        $query = "SELECT nom_categorie FROM categorie WHERE id_categorie = :id_categorie";
        
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->nom_categorie : 'No Category';
    }
}
?>
