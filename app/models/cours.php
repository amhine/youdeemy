<?php

require_once("./app/libraries/database.php");
abstract class Cours {
    protected $conn;
    protected $id_cours;
    protected $nom_cours;
    protected $date_creation;
    protected $id_categorie;
    protected $id_user;
    protected $statut;
    protected $fichier;  
    protected $type_contenu;
    protected $images;
    protected $description;
    
    public function __construct( $id_cours, $nom_cours, $date_creation, $id_categorie, $id_user, $statut, $fichier, $type_contenu, $images, $description) {
        $this->id_cours = $id_cours;
        $this->nom_cours = $nom_cours;
        $this->date_creation = $date_creation;
        $this->id_categorie = $id_categorie;
        $this->id_user = $id_user;
        $this->statut = $statut;
        $this->fichier = $fichier;  
        $this->type_contenu = $type_contenu;
        $this->images = $images;
        $this->description = $description;
        $this->conn = (new Connexion())->getConnection();
    }

    public function getIdCours() {
        return $this->id_cours;
    }
    public function setIdCours($id_cours) {
        $this->id_cours=$id_cours;
    }
    public function getNom() {
        return $this->nom_cours;  
    }
    
    public function setNom($nom_cours) {
        $this->nom_cours = $nom_cours;  
    }

    public function getDate() {
        return $this->date_creation;  
    }
    
    public function setDate($date_creation) {
        $this->date_creation = $date_creation;  
    }

    public function getuser() {
        return $this->id_user;
    }

    public function setuser($id_user) {
        $this->id_user = $id_user;
    }

    public function getstatus() {
        return $this->statut;
    }

    public function setstatus($statut) {
        $this->statut = $statut;
    }

    abstract public function getfichier();

    public function setfichier($fichier) {
        $this->fichier = $fichier;
    }

    public function getcontenu() {
        return $this->type_contenu;
    }

    public function setcontenu($type_contenu) {
        $this->type_contenu = $type_contenu;
    }

    public function getimage() {
        return $this->images;
    }

    public function setimage($images) {
        $this->images = $images;
    }

    public function getdescription() {
        return $this->description;
    }

    public function setdescription($description) {
        $this->description = $description;
    }

 
}
?>