<?php
include ''; 

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
            return $stmt->fetchAll(PDO::FETCH_OBJ); 
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
        try {
            $sql = "UPDATE categorie 
                    SET nom_categorie = :nom_categorie, 
                        description = :description 
                    WHERE id_categorie = :id_categorie";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':id_categorie', $categorie->getIdCategorie());
            $stmt->bindParam(':nom_categorie', $categorie->getNom());
            $stmt->bindParam(':description', $categorie->getDescription());
            $stmt->execute();

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour de la catégorie : " . $e->getMessage());
        }
    }

    public function getCategorieById($id_categorie) {
        try {
            $sql = "SELECT * FROM categorie WHERE id_categorie = :id_categorie";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ); 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de la catégorie : " . $e->getMessage());
        }
    }
}
?>
