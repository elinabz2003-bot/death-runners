<?php
/**
 * Routeur principal du site (Front)
 */


ini_set('session.cookie_path', '/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$raw_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$raw_uri = trim($raw_uri, '/');


$subFolder = 'monsite_exemple';
if (substr($raw_uri, 0, strlen($subFolder)) === $subFolder) {
    $uri = substr($raw_uri, strlen($subFolder));
    $uri = trim($uri, '/');
} else {
    $uri = $raw_uri;
}


if ($uri === '') {
    $uri = 'home';
}





$racine_path = './';




if ($uri === 'login') {
    require __DIR__ . '/templates/front/login.php';
    exit;
}
elseif ($uri === 'register') {
    require __DIR__ . '/templates/front/register.php';
    exit;
}
elseif ($uri === 'contact') {
    require __DIR__ . '/templates/front/form.php';
    exit;
}




if (!isset($_SESSION['user_id'])) {
    
    header("Location: /$subFolder/login");
    exit;
}


use App\Front\Model\FrontDB;
require_once $racine_path . 'model/db.php';




if ($uri === 'home') {
    
    $pdo = FrontDB::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $userDetails = $stmt->fetch(\PDO::FETCH_ASSOC);
    if (!$userDetails) {
        
        header("Location: ".$racine_path."control/logout.php");
        exit;
    }

    $titre = 'Accueil';
    ob_start();
    include($racine_path . 'templates/front/home.php');
    $main = ob_get_clean();

    
    $pageariane = 'Accueil';

    
    include($racine_path.'templates/front/header.php');
    include($racine_path.'templates/front/main.php');
    include($racine_path.'templates/front/footer.php');
    exit;
}
elseif ($uri === 'game') {
    require __DIR__ . '/templates/front/game.php';
    exit;
}
elseif ($uri === 'stats') {
    require __DIR__ . '/templates/front/stats.php';
    exit;
}
elseif ($uri === 'edit-profile') {
    require __DIR__ . '/templates/front/edit_profile.php';
    exit;
}
elseif ($uri === 'terms') {
    require __DIR__ . '/templates/front/terms.php';
    exit;
}




header("HTTP/1.0 404 Not Found");
echo "<h1>404 Not Found</h1>";
