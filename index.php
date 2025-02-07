<?php
require_once 'app/controllers/authentification.php';
require_once 'app/controllers/categorie.php';
require_once 'DAO/role.php'; 
require_once 'DAO/user.php'; 
require_once __DIR__ . "/core/router.php";
require_once __DIR__ . "/vendor/autoload.php";

session_start();

$router = new Rooter();
$router->add('GET', '/login', 'AuthController', 'showLoginForm');
$router->add('POST', '/login', 'AuthController', 'signin');
$router->add('GET', '/register', 'AuthController', 'showRegistrationForm');
$router->add('POST', '/register', 'AuthController', 'signup');

$router->add('GET', '/', 'CategorieController', 'index');
$router->add('GET', '/edit-categorie', 'CategorieController', 'getModifier');
$router->add('GET', '/edit-categorie/{id}', 'CategorieController', 'getModifier');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestMethod, $requestUri);
