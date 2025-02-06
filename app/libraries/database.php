
<?php
class Connexion {
    private $host = "localhost";
    private $username = "postgres";
    private $password = "nihad";
    private $dbname = "youdemy";
    private $conn;

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("pgsql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo "Connexion réussie à la base de données '$this->dbname'.<br>";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return $this->conn;
    }
    
}
?>