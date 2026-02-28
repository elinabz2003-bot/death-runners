<?php

$racine_path = dirname(__DIR__, 3) . '/';
$titre = 'Gestion des utilisateurs';


require_once $racine_path . 'class/User.php';
require_once $racine_path . 'admin/model/AdminDB.php';
require_once $racine_path . 'admin/model/UserManager.php';
require_once $racine_path . 'admin/model/AdminManager.php';

use App\Classes\User;
use App\Admin\Model\AdminDB;
use App\Admin\Model\UserManager;
use App\Admin\Model\AdminManager;

$error = "";
$message = "";
$allUsers = [];

try {
    $pdo = AdminDB::getPDO();
    $userMgr = new UserManager($pdo);
    $adminMgr = new AdminManager($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        
        if ($action === 'delete') {
            $idToDelete = intval($_POST['id_user'] ?? 0);
            if ($idToDelete > 0) {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id_user = :id");
                $stmt->execute(['id' => $idToDelete]);
                $message = "Utilisateur #$idToDelete supprimé.";
            }
        }
        
        elseif ($action === 'promote') {
            $idUser = intval($_POST['id_user'] ?? 0);
            $pseudo = trim($_POST['pseudo'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $mdp    = trim($_POST['mot_de_passe'] ?? '');
            if ($idUser > 0 && $pseudo && $email && $mdp) {
                if (!$adminMgr->isAdmin($idUser)) {
                    $ok = $adminMgr->createAdmin($idUser, $pseudo, $email, $mdp);
                    $message = $ok ? "Utilisateur $pseudo (#$idUser) promu en admin." : "Erreur lors de la promotion.";
                } else {
                    $error = "Cet utilisateur est déjà admin.";
                }
            } else {
                $error = "Informations insuffisantes pour promouvoir.";
            }
        }
        
        elseif ($action === 'demote') {
            $idUser = intval($_POST['id_user'] ?? 0);
            if ($idUser > 0) {
                $ok = $adminMgr->removeAdmin($idUser);
                $message = $ok ? "Utilisateur #$idUser retiré des admins." : "Impossible de retirer l'admin.";
            }
        }
    }

    
    $allUsers = $userMgr->findAll();
} catch (\PDOException $e) {
    $error = "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
}

ob_start();
?>
<section class="admin-dashboard">
    <h2>Liste des utilisateurs</h2>

    <?php if ($message): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($allUsers)): ?>
                    <?php foreach ($allUsers as $uObj): ?>
                        <?php
                        $idUser = $uObj->getIdUser();
                        $isAdmin = $adminMgr->isAdmin($idUser);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($idUser) ?></td>
                            <td><?= htmlspecialchars($uObj->getPseudo()) ?></td>
                            <td><?= htmlspecialchars($uObj->getNom()) ?></td>
                            <td><?= htmlspecialchars($uObj->getPrenom()) ?></td>
                            <td><?= htmlspecialchars($uObj->getEmail()) ?></td>
                            <td><?= $uObj->getDateInscription() ? date("d/m/Y", strtotime($uObj->getDateInscription())) : "Inconnue" ?></td>
                            <td style="display:inline-flex;gap:10px;width:100%;">
                                <!-- Formulaire de suppression -->
                                <form method="post" action="" onsubmit="return confirm('Confirmer la suppression de cet utilisateur ?');">
                                    <input type="hidden" name="id_user" value="<?= $idUser ?>">
                                    <button type="submit" name="action" value="delete">Supprimer</button>
                                </form>
                                <!-- Promotion / Dépromotion -->
                                <?php if ($isAdmin): ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="id_user" value="<?= $idUser ?>">
                                        <button type="submit" name="action" value="demote">Retirer Admin</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="id_user" value="<?= $idUser ?>">
                                        <input type="hidden" name="pseudo" value="<?= htmlspecialchars($uObj->getPseudo()) ?>">
                                        <input type="hidden" name="email" value="<?= htmlspecialchars($uObj->getEmail()) ?>">
                                        <input type="hidden" name="mot_de_passe" value="<?= htmlspecialchars($uObj->getMotDePasse()) ?>">
                                        <button type="submit" name="action" value="promote">Promouvoir</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php
$main = ob_get_clean();

include $racine_path . 'admin/templates/back/header.php';
include $racine_path . 'admin/templates/back/main.php';
include $racine_path . 'admin/templates/back/footer.php';
