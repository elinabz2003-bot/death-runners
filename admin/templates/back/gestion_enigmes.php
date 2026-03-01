<?php

$racine_path = dirname(__DIR__, 3) . '/';
$titre = 'Gestion des énigmes';


require_once $racine_path . 'class/Enigme.php';
require_once $racine_path . 'admin/model/AdminDB.php';
require_once $racine_path . 'admin/model/EnigmeManager.php';

use App\Classes\Enigme;
use App\Admin\Model\AdminDB;
use App\Admin\Model\EnigmeManager;

$error   = "";
$message = "";

try {
    $pdo       = AdminDB::getPDO();
    $enigmeMgr = new EnigmeManager($pdo);

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $titre_enigme = trim($_POST['titre_enigme'] ?? '');
            $contenu      = trim($_POST['question_enigme'] ?? '');
            $reponse      = trim($_POST['reponse_enigme'] ?? '');
            $difficulte   = trim($_POST['difficulte_enigme'] ?? '');

            if ($titre_enigme && $contenu && $reponse && $difficulte) {
                $newEnigme = new Enigme(null, $titre_enigme, $contenu, $reponse, $difficulte);
                $ok = $enigmeMgr->create($newEnigme);
                $message = $ok ? "Énigme ajoutée avec succès !" : "Erreur lors de l'ajout de l'énigme.";
            } else {
                $error = "Veuillez remplir tous les champs.";
            }
        }
        elseif ($action === 'delete') {
            $id = intval($_POST['id_enigme'] ?? 0);
            if ($id > 0) {
                $ok = $enigmeMgr->deleteById($id);
                $message = $ok ? "Énigme supprimée avec succès." : "Impossible de supprimer l'énigme.";
            } else {
                $error = "ID de l'énigme invalide.";
            }
        }
        elseif ($action === 'edit') {
            $id         = intval($_POST['id_enigme'] ?? 0);
            $titre      = trim($_POST['titre_enigme'] ?? '');
            $contenu    = trim($_POST['question_enigme'] ?? '');
            $reponse    = trim($_POST['reponse_enigme'] ?? '');
            $difficulte = trim($_POST['difficulte_enigme'] ?? '');

            if ($id > 0 && $titre && $contenu && $reponse && $difficulte) {
                $enigme = new Enigme($id, $titre, $contenu, $reponse, $difficulte);
                $ok = $enigmeMgr->update($enigme);
                $message = $ok ? "Énigme modifiée avec succès." : "Erreur lors de la modification.";
            } else {
                $error = "Tous les champs sont requis pour la modification.";
            }
        }
    }

    
    $listEnigmes = $enigmeMgr->findAll();

} catch (\PDOException $e) {
    $error = "Erreur de base de données : " . $e->getMessage();
    $listEnigmes = [];
}

ob_start();
?>
<!-- HTML de la page de gestion des énigmes -->
<h2>Gestion des énigmes</h2>

<?php if (!empty($message)): ?>
    <p style="color: #00cc66;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <p style="color: #cc0033;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Contenu</th>
            <th>Réponse</th>
            <th>Difficulté</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($listEnigmes)): ?>
        <?php foreach ($listEnigmes as $enigmeObj): ?>
        <tr>
            <form method="post" action="">
                <td><?= htmlspecialchars($enigmeObj->getIdEnigme()) ?>
                    <input type="hidden" name="id_enigme" value="<?= $enigmeObj->getIdEnigme() ?>">
                </td>
                <td><input type="text" name="titre_enigme" value="<?= htmlspecialchars($enigmeObj->getTitre()) ?>"></td>
                <td><input type="text" name="question_enigme" value="<?= htmlspecialchars($enigmeObj->getContenu()) ?>"></td>
                <td><input type="text" name="reponse_enigme" value="<?= htmlspecialchars($enigmeObj->getReponse()) ?>"></td>
                <td>
                    <select name="difficulte_enigme">
                        <option value="Facile" <?= $enigmeObj->getDifficulte() === 'Facile' ? 'selected' : '' ?>>Facile</option>
                        <option value="Moyen" <?= $enigmeObj->getDifficulte() === 'Moyen' ? 'selected' : '' ?>>Moyen</option>
                        <option value="Difficile" <?= $enigmeObj->getDifficulte() === 'Difficile' ? 'selected' : '' ?>>Difficile</option>
                    </select>
                </td>
                <td>
                    <button type="submit" name="action" value="edit">Modifier</button>
                    <button type="submit" name="action" value="delete" onclick="return confirm('Supprimer cette énigme ?');">Suppr.</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">Aucune énigme trouvée.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<h3>Ajouter une énigme</h3>
<form action="" method="POST">
    <input type="hidden" name="action" value="add">

    <label for="titre_enigme">Titre :</label>
    <input type="text" name="titre_enigme" id="titre_enigme" required>

    <label for="question_enigme">Question :</label>
    <textarea name="question_enigme" id="question_enigme" rows="3" required></textarea>

    <label for="reponse_enigme">Réponse :</label>
    <input type="text" name="reponse_enigme" id="reponse_enigme" required>

    <label for="difficulte_enigme">Difficulté :</label>
    <select name="difficulte_enigme" id="difficulte_enigme">
        <option value="Facile">Facile</option>
        <option value="Moyen">Moyen</option>
        <option value="Difficile">Difficile</option>
    </select>

    <button type="submit">Ajouter</button>
</form>

<?php
$main = ob_get_clean();
include $racine_path . 'admin/templates/back/header.php';
include $racine_path . 'admin/templates/back/main.php';
include $racine_path . 'admin/templates/back/footer.php';
