<?php
/**
 * Routeur principal du BackOffice (Administration).
 *
 * Compatible :
 * - Local en sous-dossier : /monsite_exemple/admin/...
 * - Production à la racine : /admin/...
 */

ini_set('session.cookie_path', '/');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Charge les variables d'environnement depuis .env en LOCAL (si présent).
 * En production, tu mettras les variables directement dans l'hébergeur.
 */
require_once __DIR__ . '/../env.php';
\loadEnv(__DIR__ . '/../.env'); // ne fait rien si .env n'existe pas

// -------------------------
// 1) Base paths dynamiques
// -------------------------
$subFolder = getenv('APP_SUBFOLDER') ?: ''; // ex: "monsite_exemple" en local, "" en prod

// Base URL du site
$base = $subFolder !== '' ? '/' . trim($subFolder, '/') : '';
$baseAdmin = $base . '/admin'; // ex: "/monsite_exemple/admin" ou "/admin"

// -------------------------
// 2) Récupération de l'URI demandée (sans query string)
// -------------------------
$raw_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$raw_uri = trim($raw_uri, '/');

// Exemple attendu :
// - prod : "admin/dashboard"
// - local : "monsite_exemple/admin/dashboard"

// On retire le prefix (subfolder + /admin) si présent
$prefix = trim($subFolder, '/');
$prefixAdmin = ($prefix !== '' ? $prefix . '/' : '') . 'admin';

if (substr($raw_uri, 0, strlen($prefixAdmin)) === $prefixAdmin) {
    $uri = substr($raw_uri, strlen($prefixAdmin));
    $uri = trim($uri, '/');
} else {
    // Si quelqu'un appelle /admin direct en prod, ça passe aussi
    // Ou si rewrite a déjà donné un URI "dashboard", etc.
    $uri = $raw_uri;
}

// Route par défaut du BO
if ($uri === '' || $uri === 'admin') {
    $uri = 'admin-home';
}

// -------------------------
// 3) Chemin racine relatif (pour les includes)
// -------------------------
$racine_path = '../';

// -------------------------
// 4) Vérif connexion
// -------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: {$base}/login");
    exit;
}

// -------------------------
// 5) Vérif admin
// -------------------------
require_once __DIR__ . '/model/AdminDB.php';
require_once __DIR__ . '/model/AdminManager.php';

use App\Admin\Model\AdminDB;
use App\Admin\Model\AdminManager;

$pdoAdmin = AdminDB::getPDO();
$adminMgr = new AdminManager($pdoAdmin);

if (!$adminMgr->isAdmin((int)$_SESSION['user_id'])) {
    header("Location: {$base}/home");
    exit;
}

// -------------------------
// 6) Routage BackOffice
// -------------------------
switch ($uri) {
    case 'admin-home':
        $titre = 'BackOffice - Accueil';
        ob_start();
        ?>
        <h2>Accueil BackOffice</h2>
        <p>Bienvenue dans l'interface d'administration.</p>
        <ul>
            <li><a href="<?= $baseAdmin ?>/dashboard">Tableau de bord</a></li>
            <li><a href="<?= $baseAdmin ?>/gestion_enigmes">Gestion des énigmes</a></li>
            <li><a href="<?= $baseAdmin ?>/users">Gestion des utilisateurs</a></li>

            <li><a href="<?= $base ?>/home">Retour au Front</a></li>
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