<?php

require_once("./app/libraries/database.php");
class Tag {
    private $connect;  
    private $id_tag;
    private $nom_tag;

    public function __construct($id_tag,$nom_tag) {
        $this->id_tag=$id_tag;
        $this->nom_tag=$nom_tag;
        
        $this->connect = (new Connexion())->getConnection();
    }

    public function getNomTag() {
        return $this->nom_tag;
    }

    public function getIdTag() {
        return $this->id_tag;
    }

    public function setIdTag($id_tag) {
        $this->id_tag = $id_tag;
    }


    public function setNomTag($nom_tag) {
        $this->nom_tag = $nom_tag;
    }
}
?>