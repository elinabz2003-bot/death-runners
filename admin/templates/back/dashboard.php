<?php

$titre = 'Dashboard Admin';

require_once __DIR__ . '/../../../class/User.php';
require_once __DIR__ . '/../../../class/Enigme.php';
require_once __DIR__ . '/../../../admin/model/AdminDB.php';
require_once __DIR__ . '/../../../admin/model/UserManager.php';
require_once __DIR__ . '/../../../admin/model/EnigmeManager.php';


use App\Admin\Model\AdminDB;
use App\Admin\Model\UserManager;
use App\Admin\Model\EnigmeManager;

$error       = "";
$totalUsers  = 0;
$totalEnigmes= 0;
$lastUsers   = [];

try {
    
    $pdo = AdminDB::getPDO();

    
    $userMgr   = new UserManager($pdo);
    $enigmeMgr = new EnigmeManager($pdo);

    
    $totalUsers   = $userMgr->countAll();
    $totalEnigmes = $enigmeMgr->countAll();
    $lastUsers    = $userMgr->findLastUsers(5);

} catch (\PDOException $e) {
    $error = "Erreur de lecture des stats: " . $e->getMessage();
}


ob_start();
?>
<h2>Dashboard Administration</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="dashboard-stats">
    <div class="stat-box">
        <h3>Utilisateurs</h3>
        <p><?= $totalUsers ?></p>
    </div>
    <div class="stat-box">
        <h3>Énigmes</h3>
        <p><?= $totalEnigmes ?></p>
    </div>
</div>

<h3>Derniers inscrits</h3>
<?php if (!empty($lastUsers)): ?>
    <ul>
        <?php foreach ($lastUsers as $u): ?>
            <li>
                <?= htmlspecialchars($u->getPseudo()) ?>
                (<?= date("d/m/Y", strtotime($u->getDateInscription())) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>

<?php

$main = ob_get_clean();


include __DIR__ . '/../../../admin/templates/back/header.php';
include __DIR__ . '/../../../admin/templates/back/main.php';
include __DIR__ . '/../../../admin/templates/back/footer.php';
