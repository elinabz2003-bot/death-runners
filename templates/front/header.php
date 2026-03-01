<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Header du site (Front)
 * - $racine_path : chemins côté serveur (require/include)
 * - $baseUrl     : base URL publique (liens + CSS)
 */

if (!isset($racine_path)) {
    $racine_path = '../../';
}

/**
 * Base URL auto :
 * - local : /monsite_exemple/...
 * - prod  : /...
 */
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = ($baseUrl === '' ? '/' : $baseUrl . '/');

require_once($racine_path . 'admin/model/AdminDB.php');
require_once($racine_path . 'admin/model/AdminManager.php');

use App\Admin\Model\AdminDB;
use App\Admin\Model\AdminManager;

$isAdmin = false;
try {
    if (isset($_SESSION['user_id'])) {
        $pdoAdmin = AdminDB::getPDO();
        $adminMgr = new AdminManager($pdoAdmin);
        $isAdmin = $adminMgr->isAdmin($_SESSION['user_id']);
    }
} catch (\PDOException $e) {
    // ignore
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($titre) ? $titre : 'Mon Site' ?></title>

    <!-- ✅ CSS avec chemin correct local/prod -->
    <link rel="stylesheet" href="<?= $baseUrl ?>templates/front/style.css">
</head>
<body>
<header>
    <div class="logo">
        <h1>Death Runners</h1>
    </div>

    <nav>
        <ul>
            <li><a href="<?= $baseUrl ?>home">Accueil</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= $baseUrl ?>game">Jouer</a></li>

                <?php if ($isAdmin): ?>
                    <li><a href="<?= $baseUrl ?>admin/admin-home">Administration</a></li>
                <?php endif; ?>

                <li><a href="<?= $baseUrl ?>control/logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="<?= $baseUrl ?>login">Connexion</a></li>
                <li><a href="<?= $baseUrl ?>register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>