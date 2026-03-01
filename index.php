<?php
/**
 * Routeur principal du site (Front)
 */

ini_set('session.cookie_path', '/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * ✅ Détection automatique du base path :
 * - Local (XAMPP) : /monsite_exemple
 * - Alwaysdata     : /
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// ex: "/monsite_exemple" ou "/"
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($basePath === '') {
    $basePath = '/';
}

// baseUrl toujours utilisable pour générer des liens si besoin
$baseUrl = ($basePath === '/' ? '/' : $basePath . '/');

// URI relative (sans le basePath)
$uri = $path;
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = trim($uri, '/');

if ($uri === '') {
    $uri = 'home';
}

$racine_path = './';

/**
 * Routes publiques (sans connexion)
 */
if ($uri === 'login') {
    require __DIR__ . '/templates/front/login.php';
    exit;
} elseif ($uri === 'register') {
    require __DIR__ . '/templates/front/register.php';
    exit;
} elseif ($uri === 'contact') {
    require __DIR__ . '/templates/front/form.php';
    exit;
}

/**
 * Protection : tout le reste nécessite connexion
 */
if (!isset($_SESSION['user_id'])) {
    header("Location: {$baseUrl}login");
    exit;
}

use App\Front\Model\FrontDB;
require_once $racine_path . 'model/db.php';

/**
 * Routes privées
 */
if ($uri === 'home') {

    $pdo = FrontDB::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $userDetails = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$userDetails) {
        header("Location: {$baseUrl}control/logout.php");
        exit;
    }

    $titre = 'Accueil';
    ob_start();
    include $racine_path . 'templates/front/home.php';
    $main = ob_get_clean();

    $pageariane = 'Accueil';

    include $racine_path . 'templates/front/header.php';
    include $racine_path . 'templates/front/main.php';
    include $racine_path . 'templates/front/footer.php';
    exit;

} elseif ($uri === 'game') {
    require __DIR__ . '/templates/front/game.php';
    exit;

} elseif ($uri === 'stats') {
    require __DIR__ . '/templates/front/stats.php';
    exit;

} elseif ($uri === 'edit-profile') {
    require __DIR__ . '/templates/front/edit_profile.php';
    exit;

} elseif ($uri === 'terms') {
    require __DIR__ . '/templates/front/terms.php';
    exit;
}

/**
 * 404
 */
header("HTTP/1.0 404 Not Found");
echo "<h1>404 Not Found</h1>";