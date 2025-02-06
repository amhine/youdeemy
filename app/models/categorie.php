<?php


require_once("./app/libraries/database.php");
class Categorie {

    private $id_categorie;
    private $nom_categorie;
    private $description;

    public function __construct($nom_categorie = null, $description = null, $id_categorie = null) {
        $this->id_categorie = $id_categorie;
        $this->nom_categorie = $nom_categorie;
        $this->description = $description;
    }

    public function getNom() {
        return $this->nom_categorie;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIdCategorie() {
        return $this->id_categorie;
    }

    public function setNom($nom_categorie) {
        $this->nom_categorie = $nom_categorie;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    
}
?>
