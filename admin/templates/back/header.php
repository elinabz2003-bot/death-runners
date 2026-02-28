<?php
/**
 * Header BackOffice
 *
 * Vérifie la session et le statut admin, puis affiche le menu BackOffice.
 */


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}




if (!isset($racine_path)) {
    $racine_path = '../../../';
}


require_once $racine_path . 'admin/model/AdminDB.php';
require_once $racine_path . 'admin/model/AdminManager.php';

use App\Admin\Model\AdminDB;
use App\Admin\Model\AdminManager;


try {
    if (!isset($_SESSION['user_id'])) {
        
        header("Location: /monsite_exemple/login");
        exit;
    }
    $pdoAdmin = AdminDB::getPDO();
    $adminMgr = new AdminManager($pdoAdmin);
    if (!$adminMgr->isAdmin($_SESSION['user_id'])) {
        
        header("Location: /monsite_exemple/home");
        exit;
    }
} catch (\PDOException $e) {
    
    header("Location: /monsite_exemple/home");
    exit;
}


$baseUrl = "/monsite_exemple/admin/";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Meta viewport pour le responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($titre) ? $titre : 'Administration' ?></title>
    <!-- Lien vers le CSS du BackOffice -->
    <link rel="stylesheet" href="<?= $baseUrl ?>templates/style.css">
</head>
<body>
<header>
    <div class="logo">
        <h1>BackOffice - Death Runners</h1>
    </div>
    <nav>
        <ul>
            <!-- Liens rewriting vers index.php du back (ex. admin-home, dashboard, etc.) -->
            <li><a href="<?= $baseUrl ?>admin-home">Accueil</a></li>
            <li><a href="<?= $baseUrl ?>dashboard">Tableau de bord</a></li>
            <li><a href="<?= $baseUrl ?>gestion_enigmes">Gestion Énigmes</a></li>
            <li><a href="<?= $baseUrl ?>users">Gestion Utilisateurs</a></li>
            <!-- Lien pour retourner au Front -->
            <li><a href="/monsite_exemple/home">Retour au Front</a></li>
        </ul>
    </nav>
</header>
<main>
