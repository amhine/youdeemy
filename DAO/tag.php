<?php

require_once("./app/libraries/database.php");
require_once './app/Models/tag.php';
class TagDAO {

    private $connect;

    public function __construct() {
        $this->connect = (new Connexion())->getConnection();
    }
public function addTag($nom_tag) {
    $query = "INSERT INTO tag (nom_tag) VALUES (:nom_tag)";
    $stmt = $this->connect->prepare($query);
    $stmt->bindParam(':nom_tag', $nom_tag);
    if ($stmt->execute()) {
        return $this->connect->lastInsertId();
    }

    return false;
}



public function getTags() {
    try {
        $sql = "SELECT * FROM tag";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        
        $tagObjects = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $tagObjects[] = new Tag($row->id_tag, $row->nom_tag);
        }
        return $tagObjects;
    } catch (PDOException $e) {
        echo "Error retrieving tags: " . $e->getMessage();
        return [];
    }
}



public function getTagById($id_tag) {
    $query = "SELECT * FROM tag WHERE id_tag = :id_tag";
    $stmt = $this->connect->prepare($query);
    $stmt->bindParam(':id_tag', $id_tag, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function getTagBynom($nom_tag) {
    $query = "SELECT * FROM tag WHERE nom_tag = :nom_tag";
    $stmt = $this->connect->prepare($query);
    $stmt->bindParam(':nom_tag', $nom_tag, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function getTagsByCours($id_cours) {
    $query = "SELECT tag.id_tag, tag.nom_tag FROM tag 
              JOIN courstag ON tag.id_tag = courstag.id_tag
              WHERE courstag.id_cours = :id_cours";
    $stmt = $this->connect->prepare($query);
    $stmt->bindParam(':id_cours', $id_cours, PDO::PARAM_INT);
    $stmt->execute();

    $tags = [];
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $tags[] = new Tag($row->id_tag, $row->nom_tag);
    }

    return $tags;
}

public function addTagToCours($id_cours, $id_tag) {
    $query = "INSERT INTO courstag (id_cours, id_tag) VALUES (:id_cours, :id_tag)";
    $stmt = $this->connect->prepare($query);
    $stmt->bindParam(':id_cours', $id_cours, PDO::PARAM_INT);
    $stmt->bindParam(':id_tag', $id_tag, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
public function deletetag($id_tag) {
    $query = "DELETE FROM `tag` WHERE id_tag = $id_tag";
    $stmt = $this->connect->prepare($query);
    $stmt->execute();
}
public function modifierTag($id_tag, $nom_tag) {
    try {
        $sql = "UPDATE tag SET nom_tag = :nom_tag WHERE id_tag = :id_tag";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':id_tag', $id_tag, PDO::PARAM_INT);
        $stmt->bindParam(':nom_tag', $nom_tag, PDO::PARAM_STR);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la modification du tag : " . $e->getMessage());
        return false;
    }
}
}
?>