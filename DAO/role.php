<?php

require_once("./app/libraries/database.php");
require_once './app/Models/role.php';
class RoleDAO {

    private $connect;

    public function __construct() {
        $this->connect = (new Connexion())->getConnection();
    }
    public function getRole() {
        try {
            $sql = "SELECT * FROM role WHERE nom_role NOT IN ('admin')";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            
            $roles = [];
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $roles[] = new Role($row->id_role, $row->nom_role);
            }
    
            return $roles;
        } catch (PDOException $e) {
            echo "Error retrieving roles: " . $e->getMessage();
            return [];
        }
    }
   
}
?>
