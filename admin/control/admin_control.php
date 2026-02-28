<?php
/**
 * admin_control.php
 *
 * Contrôleur pour la gestion des opérations administratives (exemple : bannir/débannir un utilisateur).
 * Traite les requêtes POST et retourne une réponse JSON.
 */


require_once __DIR__ . '/../../admin/model/AdminDB.php';
require_once __DIR__ . '/../../admin/model/AdminManager.php';
require_once __DIR__ . '/../../model/UserManager.php';

use App\Admin\Model\AdminDB;
use App\Admin\Model\AdminManager;
use App\Front\Model\UserManager as FrontUserManager;


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


$pdo = AdminDB::getPDO();
$adminMgr = new AdminManager($pdo);


if (!isset($_SESSION['user_id']) || !$adminMgr->isAdmin($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}


$action = $_POST['action'] ?? '';
$response = [];

switch ($action) {
    case 'ban':
        $userId = intval($_POST['user_id'] ?? 0);
        if ($userId > 0) {
            
            $frontUserMgr = new FrontUserManager($pdo);
            $success = $frontUserMgr->banUser($userId);
            $response = $success
                ? ['success' => true, 'message' => 'User banned successfully']
                : ['success' => false, 'message' => 'Failed to ban user'];
        } else {
            $response = ['success' => false, 'message' => 'Invalid user ID'];
        }
        break;

    case 'unban':
        $userId = intval($_POST['user_id'] ?? 0);
        if ($userId > 0) {
            $frontUserMgr = new FrontUserManager($pdo);
            $success = $frontUserMgr->unbanUser($userId);
            $response = $success
                ? ['success' => true, 'message' => 'User unbanned successfully']
                : ['success' => false, 'message' => 'Failed to unban user'];
        } else {
            $response = ['success' => false, 'message' => 'Invalid user ID'];
        }
        break;

    default:
        $response = ['success' => false, 'message' => 'Unknown action'];
}

echo json_encode($response);
exit;
