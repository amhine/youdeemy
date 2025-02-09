<?php
require_once 'app/controllers/authentification.php';
require_once 'app/controllers/categorie.php';
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/Cours.php';

require_once 'DAO/role.php'; 
require_once 'DAO/user.php'; 
require_once 'DAO/cours.php'; 
require_once __DIR__ . "/core/router.php";
require_once __DIR__ . "/vendor/autoload.php";

session_start();

$router = new Rooter();
$router->add('GET', '/login', 'AuthController', 'showLoginForm');
$router->add('POST', '/login', 'AuthController', 'signin');
$router->add('GET', '/register', 'AuthController', 'showRegistrationForm');
$router->add('POST', '/register', 'AuthController', 'signup');
$router->add('GET', '/logout', 'AuthController', 'logout');
$router->add('GET', '/attendre', 'AuthController', 'attendre');

$router->add('GET', '/categorie', 'CategorieController', 'index');
$router->add('GET', '/encategorie', 'CategorieController', 'encategorie');
$router->add('GET', '/voircategorie/{id}', 'CategorieController', 'details');
$router->add('GET', '/edit-categorie/{id}', 'CategorieController', 'getModifier');


$router->add('GET', '/courses', 'CoursController', 'index');
$router->add('GET', '/encourses', 'CoursController', 'getUserCourses');
$router->add('GET', '/cours/{id_cours}', 'CoursController', 'getCoursById');
$router->add('GET', '/inscription/{id_cours}', 'CoursController', 'inscrire');
$router->add('GET', '/ajoutercours', 'CoursController', 'showAjoutForm');
$router->add('POST', '/validerajout', 'CoursController', 'validerAjout');
$router->add('GET', '/supprimer/{id_cours}', 'CoursController', 'deleteCours');
$router->add('POST', '/modifier/{id_cours}', 'CoursController', 'modifierCours');
$router->add('GET', '/modifie/{id_cours}', 'CoursController', 'showModifForm');



$router->add('GET', '/home', 'HomeController', 'index');
$router->add('GET', '/enhome', 'HomeController', 'index');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestMethod, $requestUri);
