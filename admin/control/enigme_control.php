<?php
/**
 * enigme_control.php
 *
 * Contrôleur pour la gestion des énigmes (BackOffice).
 * Traite les requêtes AJAX POST pour ajouter, modifier ou supprimer une énigme
 * et retourne une réponse JSON.
 */


require_once __DIR__ . '/../../admin/model/AdminDB.php';
require_once __DIR__ . '/../../admin/model/EnigmeManager.php';
require_once __DIR__ . '/../../class/Enigme.php';

use App\Admin\Model\AdminDB;
use App\Admin\Model\EnigmeManager;
use App\Classes\Enigme;


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


$pdo = AdminDB::getPDO();
$enigmeMgr = new EnigmeManager($pdo);

$action = $_POST['action'] ?? '';
$response = [];

switch ($action) {
    case 'add':
        $titre    = trim($_POST['titre'] ?? '');
        $contenu  = trim($_POST['contenu'] ?? '');
        $reponse  = trim($_POST['reponse'] ?? '');
        $diff     = trim($_POST['difficulte'] ?? '');
        if ($titre && $contenu && $reponse && $diff) {
            $enigme = new Enigme(null, $titre, $contenu, $reponse, $diff);
            $ok = $enigmeMgr->create($enigme);
            $response = $ok
                ? ['success' => true, 'message' => 'Enigme added successfully']
                : ['success' => false, 'message' => 'Failed to add enigme'];
        } else {
            $response = ['success' => false, 'message' => 'Missing fields'];
        }
        break;

    case 'edit':
        $id       = intval($_POST['id'] ?? 0);
        $titre    = trim($_POST['titre'] ?? '');
        $contenu  = trim($_POST['contenu'] ?? '');
        $reponse  = trim($_POST['reponse'] ?? '');
        $diff     = trim($_POST['difficulte'] ?? '');
        if ($id > 0 && $titre && $contenu && $reponse && $diff) {
            $enigme = new Enigme($id, $titre, $contenu, $reponse, $diff);
            $ok = $enigmeMgr->update($enigme);
            $response = $ok
                ? ['success' => true, 'message' => 'Enigme updated successfully']
                : ['success' => false, 'message' => 'Failed to update enigme'];
        } else {
            $response = ['success' => false, 'message' => 'Missing fields'];
        }
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $ok = $enigmeMgr->deleteById($id);
            $response = $ok
                ? ['success' => true, 'message' => 'Enigme deleted successfully']
                : ['success' => false, 'message' => 'Failed to delete enigme'];
        } else {
            $response = ['success' => false, 'message' => 'Invalid enigme ID'];
        }
        break;

    default:
        $response = ['success' => false, 'message' => 'Unknown action'];
}

echo json_encode($response);
exit;
