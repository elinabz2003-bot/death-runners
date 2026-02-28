<?php
/**
 * Vue "stats" : affiche les statistiques de l'utilisateur.
 *
 * PRÉREQUIS (gérés par le routeur index.php) :
 *  - session_start() déjà fait,
 *  - $_SESSION['user_id'] vérifié (sinon redirection).
 *  - On peut charger la BDD ici si on veut récupérer des infos (score, progression).
 */




if (!isset($racine_path)) {
    $racine_path = '../../';
}


require_once $racine_path . 'model/db.php';
use App\Front\Model\FrontDB;

$pdo = FrontDB::getPDO();


$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userDetails) {
    
    header("Location: " . $racine_path . "control/logout.php");
    exit;
}


$progressStmt = $pdo->prepare("
    SELECT p.*, e.titre
    FROM progression p
    LEFT JOIN enigmes e ON p.id_enigme = e.id_enigme
    WHERE p.id_user = :id
    ORDER BY p.date_resolution DESC
");
$progressStmt->execute(['id' => $_SESSION['user_id']]);
$progressions = $progressStmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
?>
<section class="stats-section">
    <div class="profile-container">
        <h2>Statistiques de <?= htmlspecialchars($userDetails['pseudo']) ?></h2>
        <p>Score total : <?= htmlspecialchars($userDetails['score']) ?></p>
        
        <h3>Progression des énigmes</h3>
        <?php if (count($progressions) > 0): ?>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Énigme</th>
                        <th>État</th>
                        <th>Temps pris (sec)</th>
                        <th>Date de résolution</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($progressions as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['titre'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($p['etat']) ?></td>
                        <td><?= htmlspecialchars($p['temps_pris']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($p['date_resolution'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune progression enregistrée.</p>
        <?php endif; ?>
    </div>
</section>
<?php
$main = ob_get_clean();
$titre = 'Statistiques';


include($racine_path . 'templates/front/header.php');
include($racine_path . 'templates/front/main.php');
include($racine_path . 'templates/front/footer.php');
