<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Header du site (Front)
 *
 * Affiche l'en-tête et la navigation.
 * Utilise :
 * - $racine_path pour les inclusions côté serveur.
 * - $baseUrl pour générer les URLs publiques.
 */


if (!isset($racine_path)) {
    $racine_path = '../../';
}


$baseUrl = "/monsite_exemple/";


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
    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($titre) ? $titre : 'Mon Site' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>templates/front/style.css">
    <!-- Autres inclusions -->
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
