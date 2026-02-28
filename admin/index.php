<?php
/**
 * Routeur principal du BackOffice (Administration).
 */


ini_set('session.cookie_path', '/');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$raw_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$raw_uri = trim($raw_uri, '/');


$subFolder = 'monsite_exemple/admin';
if (substr($raw_uri, 0, strlen($subFolder)) === $subFolder) {
    $uri = substr($raw_uri, strlen($subFolder));
    $uri = trim($uri, '/');
} else {
    $uri = $raw_uri;
}


if ($uri === '') {
    $uri = 'admin-home';
}



$racine_path = '../';


if (!isset($_SESSION['user_id'])) {
    
    header("Location: /monsite_exemple/login");
    exit;
}


require_once __DIR__ . '/model/AdminDB.php';
require_once __DIR__ . '/model/AdminManager.php';

use App\Admin\Model\AdminDB;
use App\Admin\Model\AdminManager;

$pdoAdmin = AdminDB::getPDO();
$adminMgr = new AdminManager($pdoAdmin);

if (!$adminMgr->isAdmin($_SESSION['user_id'])) {
    
    header("Location: /monsite_exemple/home");
    exit;
}


switch ($uri) {
    case 'admin-home':
        $titre = 'BackOffice - Accueil';
        ob_start();
        ?>
        <h2>Accueil BackOffice</h2>
        <p>Bienvenue dans l'interface d'administration.</p>
        <ul>
            <!-- Liens rewriting vers les routes du BackOffice -->
            <li><a href="/monsite_exemple/admin/dashboard">Tableau de bord</a></li>
            <li><a href="/monsite_exemple/admin/gestion_enigmes">Gestion des énigmes</a></li>
            <li><a href="/monsite_exemple/admin/users">Gestion des utilisateurs</a></li>
            <!-- Retour au FrontOffice -->
            <li><a href="/monsite_exemple/home">Retour au Front</a></li>
        </ul>
        <?php
        $main = ob_get_clean();
        include $racine_path . 'admin/templates/back/header.php';
        include $racine_path . 'admin/templates/back/main.php';
        include $racine_path . 'admin/templates/back/footer.php';
        break;

    case 'dashboard':
        require __DIR__ . '/templates/back/dashboard.php';
        break;

    case 'gestion_enigmes':
        require __DIR__ . '/templates/back/gestion_enigmes.php';
        break;

    case 'users':
        require __DIR__ . '/templates/back/users.php';
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        break;
}

exit;
