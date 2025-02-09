<?php

require_once("./app/libraries/database.php");
require_once("./DAO/categoriedao.php");
require_once './DAO/cours.php';
require_once './DAO/user.php';

class CoursController {
    private $coursDAO;
    private $categorieDAO;

    public function __construct() {
        $this->coursDAO = new CoursDAO();
        $this->categorieDAO = new CategorieDAO();
    }
    public function index() {
        if (!isset($_SESSION['id_user'])) {
            header("Location: index.php?action=signin");
            exit;
        }
        $cours = $this->coursDAO->getAllCours();
        require_once 'app/views/etudient/cours.php';
    }

    public function getCoursById($id_cours) {
        $cours = $this->coursDAO->getCoursById($id_cours);
        if ($cours) {
            require_once 'app/views/etudient/cours_voir.php';
        } else {
            echo "Cours non trouvé.";
            exit;
        }
    }
    public function createCours($data) {
        $cours = new Cours(
            null, 
            $data['nom_cours'], 
            $data['date_creation'], 
            $data['id_categorie'], 
            $data['id_user'], 
            $data['statut'], 
            $data['fichier'], 
            $data['type_contenu'], 
            $data['images'],
            $data['description']
        );
        return $this->coursDAO->save($cours);
    }

    public function getCours($id_cours) {
        return $this->coursDAO->getCoursById($id_cours);
    }

    public function getAllCours() {
        return $this->coursDAO->getAllCours();
    }

public function inscrire($id_cours) {
    $id_etudiant = $_SESSION['id_user'];
    $cours = $this->coursDAO->getCoursById($id_cours);
    
    if (!$cours) {
        header("Location: /error?message=Cours%20non%20trouvé");
        exit();
    }
    $userDAO = new UserDAO();
    if ($userDAO->estInscrit($id_etudiant, $id_cours)) {
        require_once 'app/views/etudient/detailscours.php';
        exit();
    }
    $success = $userDAO->inscrireAuCours($id_etudiant, $id_cours);

    if ($success) {
        require_once 'app/views/etudient/detailscours.php';
    } else {
        header("Location: /cours/" . $id_cours . "&error=1");
    }

    exit();
}

public function getCourById() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: index.php?action=signin");
        exit;
    }
    $id_user = $_SESSION['id_user'];
    $cours = $this->coursDAO->getCoursById($id_user);
    if ($cours) {
        require_once 'app/views/enseignent/cours.php'; 
    } else {
        echo "Aucun cours trouvé pour cet utilisateur.";
    }
}
public function getUserCourses() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: /login");
        exit;
    }
    
    $id_user = $_SESSION['id_user'];
    $cours = $this->coursDAO->getCoursByUser($id_user);
    
    require_once 'app/views/enseignent/cours.php';
}
public function showAjoutForm() {
    $categories = $this->categorieDAO->getCategories();
    require_once  './app/views/enseignent/ajoutercours.php';
}

public function validerAjout() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: index.php?action=signin");
        exit;
    }

    $nom_cours = $_POST['nom_cours'];
    $images = $_POST['images'];
    $description = $_POST['description'];
    $type_contenu = $_POST['type_contenu']; 
    $categorie = $_POST['categorie'];
    $fichier = $_POST['fichier'];

    if (empty($nom_cours) || empty($images) || empty($description) || $type_contenu == "0" || $categorie == "0" || empty($fichier)) {
        header("Location: /ajoutercours?error=1");
        exit;
    }

    if ($type_contenu == 'document') {
        $cours = new CoursDocument(
            null,
            $nom_cours,
            date('Y-m-d H:i:s'),
            $categorie,
            $_SESSION['id_user'],
            'actif',
            $fichier,
            $images,
            $description
        );
    } else if ($type_contenu == 'video') {
        $cours = new CoursVideo(
            null,
            $nom_cours,
            date('Y-m-d H:i:s'),
            $categorie,
            $_SESSION['id_user'],
            'actif',
            $fichier,
            $images,
            $description
        );
    } else {
        header("Location: /ajoutercours?error=2");
        exit;
    }

    $coursDAO = new CoursDAO();
    $coursDAO->save($cours);

    // $tags = isset($_POST['nom_tag']) ? $_POST['nom_tag'] : [];
    // if (!empty($tags)) {
    //     foreach ($tags as $tag) {
    //         $coursDAO->addTag($cours->getIdCours(), $tag);
    //     }
    // }

    header("Location: /encourses");
    exit;
}


public function showModifForm($id_cours) {
    try {
        if (!isset($_SESSION['id_user'])) {
            header("Location: /signin");
            exit;
        }

        $cours = $this->coursDAO->getCoursById($id_cours);
        
        if (!$cours) {
            $_SESSION['error'] = "Cours non trouvé.";
            header("Location: /encourses");
            exit;
        }

        $categories = $this->categorieDAO->getCategories();
        
        if (!$categories) {
            $categories = [];
        }
        require_once './app/views/enseignent/modifiercours.php';

    } catch (Exception $e) {
        $_SESSION['error'] = "Une erreur est survenue : " . $e->getMessage();
        header("Location: /encourses");
        exit;
    }
}



public function modifierCours($id_cours) {
    if (!isset($_SESSION['id_user'])) {
        header("Location: /signin");
        exit;
    }
    $cours = $this->coursDAO->getCoursById($id_cours);

    if (!$cours) {
        $_SESSION['error'] = "Cours non trouvé.";
        header("Location: /encourses");
        exit;
    }
    $categories = $this->categorieDAO->getCategories();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'];
        $categorie = $_POST['categorie'];
        $fichier = $_POST['fichier'];
        $statut = $_POST['statut'];
        $type_cours = $_POST['type_cours'];
        $images = $_POST['images'];
        $description = $_POST['description'];
        if (empty($titre) || empty($images) || empty($description) || $type_cours == "0" || $categorie == "0" || empty($fichier)) {
            $_SESSION['error'] = "Tous les champs doivent être remplis.";
            header("Location: /modifier/$id_cours");
            exit;
        }
        $cours->setNom($titre);
        $cours->setCategorie($categorie);
        $cours->setFichier($fichier);
        $cours->setStatus($statut);
        $cours->setContenu($type_cours);
        $cours->setImage($images);
        $cours->setDescription($description);

        $resultat = $this->coursDAO->updateCours($cours);
        if ($resultat) {
            $_SESSION['success'] = "Cours mis à jour avec succès.";
            header("Location: /encourses");
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du cours.";
            header("Location: /modifier/$id_cours");
            exit;
        }
    }
    require_once './app/views/enseignent/modifiercours.php';
}

}
?>