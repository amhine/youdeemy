<?php

require_once("./app/libraries/database.php");
require_once("./DAO/tag.php");
abstract class Cours {
    protected $conn;
    protected $id_cours;
    protected $titre;
    protected $date_creation;
    protected $id_categorie;
    protected $id_user;
    protected $statut;
    protected $fichier;  
    protected $type_contenu;
    protected $images;
    protected $description;
    
    public function __construct( $id_cours, $titre, $date_creation, $id_categorie, $id_user, $statut, $fichier, $type_contenu, $images, $description) {
        $this->id_cours = $id_cours;
        $this->titre = $titre;
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
        return $this->titre;  
    }
    
    public function setNom($titre) {
        $this->titre = $titre;  
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
    public function getcategorie() {
        return $this->id_categorie;
    }

    public function setcategorie($id_categorie) {
        $this->id_categorie = $id_categorie; 
    }
    public function getNomCategorie() {
        $categorieDAO = new CategorieDAO();
        return $categorieDAO->getCategorieNom($this->id_categorie);
    }
    public function getNomTag() {
        $tagDAO = new TagDAO();
        $tags = $tagDAO->getTagsByCours($this->id_cours); 
        if (!empty($tags)) {
            $tagNames = [];
            foreach ($tags as $tag) {
                $tagNames[] = $tag->getNomTag(); 
            }
            return implode(", ", $tagNames); 
        } else {
            return "Aucun tag associé"; 
        }
    }

    
    
    
    
    
   
}
class CoursDocument extends Cours {
    public function __construct($id_cours, $titre, $date_creation, $id_categorie, $id_user, $statut, $fichier, $images, $description) {
        parent::__construct($id_cours, $titre, $date_creation, $id_categorie, $id_user, $statut, $fichier, 'document', $images, $description);
    }

    public function getFichier() {
        return $this->fichier;
    }
}
class CoursVideo extends Cours {
    public function __construct($id_cours, $titre, $date_creation, $id_categorie, $id_user, $statut, $fichier, $images, $description) {
        
        parent::__construct($id_cours, $titre, $date_creation, $id_categorie, $id_user, $statut, $fichier, 'video', $images, $description);
    }

    public function getFichier() {
        return $this->fichier;
    }
}

?>