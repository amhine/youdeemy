<?php 

require_once("./app/libraries/database.php");
class Role {
    private $id_role;
    private $nom_role;
    private $connect;

    public function __construct($id_role, $nom_role) {
        $this->id_role = $id_role;
        $this->nom_role=$nom_role;
        $this->connect = (new Connexion())->getConnection();
    }
   

    public function getNomRole() {
        return $this->nom_role;
    }
    public function setnomrole($nom_role){
        $this->nom_role=$nom_role;
    }

    
    
}
?>