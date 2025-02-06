<?php

require_once("./app/libraries/database.php");
require_once("./DAO/role.php");

class RoleController {
    private $RoleDAO;

    public function __construct(RoleDAO $RoleDAO) {
        $this->RoleDAO = $RoleDAO;
    }

    public function getRoles() {
        try {
            $roles = $this->RoleDAO->getRole();
            return $roles;
        } catch (Exception $e) {
            error_log("Error in RoleController: " . $e->getMessage());
            return [];
        }
    }

}
?>