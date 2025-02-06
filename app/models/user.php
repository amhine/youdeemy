<?php

require_once("./app/libraries/database.php");
class Utilisateur {
    protected $id_user;
    protected $nom_user;
    protected $email;
    protected $password;
    protected $id_role;
    protected $date_creation;
    protected $connect;
    protected $id_cours;
    protected $date_inscription;

    public function __construct($id_user = null, $nom_user = null, $email = null, $password = null, $id_role = null, $date_creation = null) {
        $this->id_user = $id_user;
        $this->nom_user = $nom_user;
        $this->email = $email;
        $this->password = $password;
        $this->id_role = $id_role;
        $this->date_creation = $date_creation;
        $this->connect = (new Connexion())->getConnection();
    }

    public function getiduser(){
        return $this->id_user;
    }

    public function setiduser($id_user){
        $this->id_user=$id_user;
    }

    public function getnomuser(){
        return $this->nom_user;
    }
    public function setnomuser($nom_user){
        $this->nom_user=$nom_user;
    }
    public function getemail(){
        return $this->email;
    }
    public function setemail($email){
    $this->email=$email;
    }

    public function getpassword(){
        return $this->password;
    }
    public function setpassword($password){
    $this->password=$password;
    }

    public function getidrole(){
        return $this->id_role;
    }
   
}
?>