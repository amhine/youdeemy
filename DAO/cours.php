<?php 
require_once(__DIR__ . "/../app/libraries/database.php");
require_once(__DIR__ . "/../app/Models/cours.php");

class CoursDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Connexion())->getConnection();
    }

    public function save(Cours $cours) {
        try {
            $query = "INSERT INTO cours (titre, date_creation, id_categorie, id_user, statut,type_cours, fichier, images, description)
                      VALUES (:titre, :date_creation, :id_categorie, :id_user, :statut, :type_cours, :fichier, :images, :description)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $cours->getNom());
            $stmt->bindParam(':date_creation', $cours->getDate());
            $stmt->bindParam(':id_categorie', $cours->getCategorie());
            $stmt->bindParam(':id_user', $cours->getUser());
            $stmt->bindParam(':statut', $cours->getstatus());
            $stmt->bindParam(':type_cours', $cours->getcontenu());
            $stmt->bindParam(':fichier', $cours->getfichier());
            $stmt->bindParam(':images', $cours->getimage());
            $stmt->bindParam(':description', $cours->getdescription());
            
            $stmt->execute();
            return $this->conn->lastInsertId(); 
        } catch (PDOException $e) {
            error_log("Erreur lors de la sauvegarde du cours : " . $e->getMessage());
            return false;
        }
    }

    public function getCoursById($id_cours) {
        $query = "SELECT * FROM cours WHERE id_cours = :id_cours";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cours', $id_cours, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);  
        if ($result) {
            if ($result->type_cours === 'document') {
                return new CoursDocument(
                    $result->id_cours, 
                    $result->titre, 
                    $result->date_creation, 
                    $result->id_categorie, 
                    $result->id_user, 
                    $result->statut, 
                    $result->fichier,
                    $result->images,
                    $result->description
                );
            } elseif ($result->type_cours=== 'video') {
                return new CoursVideo(
                    $result->id_cours, 
                    $result->titre, 
                    $result->date_creation, 
                    $result->id_categorie, 
                    $result->id_user, 
                    $result->statut, 
                    $result->fichier,
                    $result->images,
                    $result->description
                );
            }
        }
        return null;
    }
  
    public function getCoursByUser($id_user) {
        $query = "SELECT * FROM cours WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        
        $cours = [];
        while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
            if ($result->type_cours === 'document') {
                $cours[] = new CoursDocument(
                    $result->id_cours,
                    $result->titre,
                    $result->date_creation,
                    $result->id_categorie,
                    $result->id_user,
                    $result->statut,
                    $result->fichier,
                    $result->images,
                    $result->description
                );
            } elseif ($result->type_cours === 'video') {
                $cours[] = new CoursVideo(
                    $result->id_cours,
                    $result->titre,
                    $result->date_creation,
                    $result->id_categorie,
                    $result->id_user,
                    $result->statut,
                    $result->fichier,
                    $result->images,
                    $result->description
                );
            }
        }
        return $cours;
    }
  

    public function getAllCours() {
        $query = "SELECT * FROM cours";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $coursList = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if ($row->type_cours == 'document') {
    $coursList[] = new CoursDocument(
        $row->id_cours, 
        $row->titre, 
        $row->date_creation, 
        $row->id_categorie, 
        $row->id_user, 
        $row->statut, 
        $row->fichier,
        $row->images,
        $row->description
    );
} elseif ($row->type_cours == 'video') {
    $coursList[] = new CoursVideo(
        $row->id_cours, 
        $row->titre, 
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
        return $coursList;
    }
    

   

    private function getCoursPaginated($search, $type_cours, $limit, $offset) {
        $query = "SELECT * FROM cours WHERE type_cours = :type_cours AND titre LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':type_cours', $type_cours);
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        $coursList = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if ($type_cours === 'document') {
                $coursList[] = new CoursDocument(
                    $row->id_cours, 
                    $row->titre, 
                    $row->date_creation, 
                    $row->id_categorie, 
                    $row->id_user, 
                    $row->statut, 
                    $row->fichier,
                    $row->images,
                    $row->description
                );
            } elseif ($type_cours === 'video') {
                $coursList[] = new CoursVideo(
                    $row->id_cours, 
                    $row->titre, 
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
        return $coursList;
    }
    


    
    public function getAllCourses($search, $page, $limit) {
        $offset = ($page - 1) * $limit;
    
        $query = "SELECT * FROM cours WHERE titre LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%" . $search . "%"; 
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        $coursList = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if ($row->type_cours === 'document') {
                $coursList[] = new CoursDocument(
                    $row->id_cours, 
                    $row->titre, 
                    $row->date_creation, 
                    $row->id_categorie, 
                    $row->id_user, 
                    $row->statut, 
                    $row->fichier,
                    $row->images,
                    $row->description
                );
            } elseif ($row->type_cours === 'video') {
                $coursList[] = new CoursVideo(
                    $row->id_cours, 
                    $row->titre, 
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
        return $coursList;
    }
    
    public function countCours($search) {
        $query = "SELECT COUNT(*) FROM cours WHERE titre LIKE :search";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%" . $search . "%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    public function searchCourses($searchTerm, $limit, $offset) {
        $sql = "SELECT * FROM cours WHERE nom LIKE :searchTerm LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countSearchResults($searchTerm) {
        $sql = "SELECT COUNT(*) FROM cours WHERE nom LIKE :searchTerm";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function deleteCours($id_cours) {
        try {
            $query = "DELETE FROM cours WHERE id_cours = :id_cours";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_cours', $id_cours, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false; 
            }
        } catch (Exception $e) {
            echo "Erreur lors de la suppression du cours: " . $e->getMessage();
            return false; 
        }
    }

    public function updateCours($cours) {
        try {
            $sql = "UPDATE public.cours
                    SET titre = :titre,
                        date_creation = :date_creation,
                        id_categorie = :id_categorie,
                        id_user = :id_user,
                        fichier = :fichier,
                        statut = :statut,
                        type_cours = :type_cours,
                        images = :images,
                        description = :description
                    WHERE id_cours = :id_cours";
            $stmt = $this->conn->prepare($sql);
    
            $stmt->bindParam(':id_cours', $cours->getIdCours());
            $stmt->bindParam(':titre', $cours->getNom());
            $stmt->bindParam(':date_creation', $cours->getDate());
            $stmt->bindParam(':id_categorie', $cours->getCategorie());
            $stmt->bindParam(':id_user', $cours->getUser());
            $stmt->bindParam(':fichier', $cours->getFichier());
            $stmt->bindParam(':statut', $cours->getStatus());
            $stmt->bindParam(':type_cours', $cours->getContenu());
            $stmt->bindParam(':images', $cours->getImage());
            $stmt->bindParam(':description', $cours->getDescription());
    
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du cours : " . $e->getMessage());
        }
    }
    




















//     public function modifier(Cours $cours) {
//         try {
//             // Préparer la requête SQL de mise à jour
//             $query = "UPDATE public.cours
//                       SET titre = :titre,
//                           date_creation = :date_creation,
//                           id_categorie = :id_categorie,
//                           id_user = :id_user,
//                           fichier = :fichier,
//                           statut = :statut,
//                           type_cours = :type_cours,
//                           images = :images,
//                           description = :description
//                       WHERE id_cours = :id_cours";
    
//             // Préparation de la requête
//             $stmt = $this->conn->prepare($query);
    
//             // Liaison des paramètres
//          // Récupérer les valeurs des méthodes dans des variables
// $id_cours = $cours->getIdCours();
// $titre = $cours->getNom();
// $date_creation = $cours->getDate();
// $id_categorie = $cours->getCategorie();
// $id_user = $cours->getUser();
// $fichier = $cours->getFichier();
// $statut = $cours->getStatus();
// $type_cours = $cours->getContenu();
// $images = $cours->getImage();
// $description = $cours->getDescription();

// // Lier les paramètres avec les variables
// $stmt->bindParam(':id_cours', $id_cours);
// $stmt->bindParam(':titre', $titre);
// $stmt->bindParam(':date_creation', $date_creation);
// $stmt->bindParam(':id_categorie', $id_categorie);
// $stmt->bindParam(':id_user', $id_user);
// $stmt->bindParam(':fichier', $fichier);
// $stmt->bindParam(':statut', $statut);
// $stmt->bindParam(':type_cours', $type_cours);
// $stmt->bindParam(':images', $images);
// $stmt->bindParam(':description', $description);

    
//             // Exécution de la requête
//             $stmt->execute();
    
//             return true; // Si l'exécution est réussie
//         } catch (PDOException $e) {
//             // Gestion des erreurs
//             error_log("Erreur lors de la mise à jour du cours : " . $e->getMessage());
//             return false;
//         }
//     }
    
    
    
    
    
}

   

?>
