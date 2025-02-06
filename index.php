<?php
// require_once 'app/controllers/authentification.php';
// require_once 'DAO/role.php'; 
// require_once 'DAO/user.php';  

// $authController = new AuthController();

// $action = $_GET['action'] ?? 'index';

// switch ($action) {
//     case 'home':
//         $authController->showRegistrationForm();
//         break;
//         case 'register':
//             $authController->signup();
//             break;
//     case 'login':
//         // $authController->signin();
//         break;
//     default:
//         echo "Page non trouvée";
//         break;
// }
?>
<?php
require_once 'app/controllers/authentification.php';
require_once 'DAO/role.php'; 
require_once 'DAO/user.php';  

$authController = new AuthController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
        $authController->showLoginForm();
        break;
    case 'home':
        $authController->showRegistrationForm();
        break;
    case 'register':
        $authController->signup();
        break;
    case 'signin':
        $authController->showLoginForm();
        break;
    case 'login':
        $authController->signin();
        break;
    case 'logout':
        $authController->logout();
        break;

    default:
        echo "Page non trouvée";
        break;
}
?>