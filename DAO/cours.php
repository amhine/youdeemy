class coursDao {

private $conn;

public function __construct() {
    $this->conn = (new Connexion())->getConnection();
}

public function getCategorie($id_categorie) {
    return $this->getCategorieById($id_categorie);
}

public function getCategorieById($id_categorie) {
    $query = "SELECT * FROM categorie WHERE id_categorie = :id_categorie";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
    $stmt->execute();
    $categorie = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($categorie) {
        return new Categorie($categorie->id_categorie, $categorie->nom);
    }
    return null;
}

public function getUserById($id_user) {
    $query = "SELECT * FROM utilisateur WHERE id_user = :id_user";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function save() {
    try {
        $query = "INSERT INTO cours (nom_cours, date_creation, id_categorie, id_user, statut, type_contenu, fichier, images, description)
                  VALUES (:nom_cours, :date_creation, :id_categorie, :id_user, :statut, :type_contenu, :fichier, :images, :description)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_cours', $this->nom_cours);
        $stmt->bindParam(':date_creation', $this->date_creation);
        $stmt->bindParam(':id_categorie', $this->id_categorie);
        $stmt->bindParam(':id_user', $this->id_user);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':type_contenu', $this->type_contenu);
        $stmt->bindParam(':fichier', $this->fichier);
        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':description', $this->description);
        
        if ($stmt->execute()) {
            $this->id_cours = $this->conn->lastInsertId(); 
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Erreur lors de la sauvegarde du cours : " . $e->getMessage());
        return false;
    }
}

public function getCoursPaginated($limit = 9, $offset = 0, $search = '') { 
    $query = "SELECT * FROM cours WHERE statut = 'Actif'";
    if (!empty($search)) {
        $query .= " AND nom_cours LIKE :search";
    }
    $query .= " ORDER BY date_creation DESC LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($query);
    if (!empty($search)) {
        $searchTerm = "%$search%";
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $cours = [];
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        if ($row->type_contenu == 'document') {
            $cours[] = new CoursDocument(
                $this->conn, 
                $row->id_cours, 
                $row->nom_cours, 
                $row->date_creation, 
                $row->id_categorie, 
                $row->id_user, 
                $row->statut, 
                $row->fichier,
                $row->images,
                $row->description
            );
        } elseif ($row->type_contenu == 'video') {
            $cours[] = new CoursVideo(
                $this->conn, 
                $row->id_cours, 
                $row->nom_cours, 
                $row->date_creation, 
                $row->id_categorie, 
                $row->id_user, 
                $row->statut, 
                $row->fichier,
                $row->images,
                $row->description
            );
        }
    }

    return $cours;
}

// Continue with other methods similarly...
}
