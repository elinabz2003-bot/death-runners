<?php
/**
 * Header BackOffice
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

/**
 * Base URL admin auto :
 * - local : /monsite_exemple/admin/
 * - prod  : /admin/
 */
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = ($baseUrl === '' ? '/' : $baseUrl . '/');

/**
 * Base URL front (parent de /admin/)
 * - local : /monsite_exemple/
 * - prod  : /
 */
$frontBaseUrl = rtrim(dirname(rtrim($baseUrl, '/')), '/\\');
$frontBaseUrl = ($frontBaseUrl === '' ? '/' : $frontBaseUrl . '/');

try {
    if (!isset($_SESSION['user_id'])) {
        header("Location: {$frontBaseUrl}login");
        exit;
    }

    $pdoAdmin = AdminDB::getPDO();
    $adminMgr = new AdminManager($pdoAdmin);

    if (!$adminMgr->isAdmin($_SESSION['user_id'])) {
        header("Location: {$frontBaseUrl}home");
        exit;
    }
} catch (\PDOException $e) {
    header("Location: {$frontBaseUrl}home");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($titre) ? $titre : 'Administration' ?></title>

    <!-- ✅ CSS backoffice -->
    <link rel="stylesheet" href="<?= $baseUrl ?>templates/style.css">
</head>
<body>
<header>
    <div class="logo">
        <h1>BackOffice - Death Runners</h1>
    </div>

    <nav>
        <ul>
            <li><a href="<?= $baseUrl ?>admin-home">Accueil</a></li>
            <li><a href="<?= $baseUrl ?>dashboard">Tableau de bord</a></li>
            <li><a href="<?= $baseUrl ?>gestion_enigmes">Gestion Énigmes</a></li>
            <li><a href="<?= $baseUrl ?>users">Gestion Utilisateurs</a></li>
            <li><a href="<?= $frontBaseUrl ?>home">Retour au Front</a></li>
        </ul>
    </nav>
</header>
<main>