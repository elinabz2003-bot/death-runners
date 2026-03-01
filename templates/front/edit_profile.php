<?php
/**
 * Page de modification du profil utilisateur.
 *
 * PRÉREQUIS :
 * - La session est déjà démarrée par le routeur (index.php).
 * - $_SESSION['user_id'] est défini pour identifier l'utilisateur connecté.
 * - $racine_path pointe vers la racine du projet (../../ depuis templates/front).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($racine_path)) {
    $racine_path = '../../';
}

/**
 * ✅ Base URL auto :
 * - local : /monsite_exemple/
 * - prod  : /
 */
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($basePath === '') {
    $basePath = '/';
}
$baseUrl = ($basePath === '/' ? '/' : $basePath . '/');

if (!isset($_SESSION['user_id'])) {
    header("Location: {$baseUrl}login");
    exit;
}

require_once $racine_path . 'model/db.php';
use App\Front\Model\FrontDB;

$pdo = FrontDB::getPDO();

// Charger infos actuelles
$stmt = $pdo->prepare("SELECT avatar, bio FROM users WHERE id_user = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$userRow = $stmt->fetch(\PDO::FETCH_ASSOC);

if (!$userRow) {
    header("Location: {$baseUrl}control/logout.php");
    exit;
}

$error = "";
$success = "";

// Uploads : chemin serveur + chemin web
$uploadDirFs  = rtrim($racine_path, '/\\') . "/uploads/avatars/";  // filesystem
$uploadDirWeb = $baseUrl . "uploads/avatars/";                    // URL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newBio = trim($_POST['bio'] ?? '');

    // avatar actuel
    $newAvatar = $userRow['avatar'] ?? '';

    // upload d'un nouvel avatar ?
    if (!empty($_FILES['avatar']['name'])) {

        if (!is_dir($uploadDirFs)) {
            mkdir($uploadDirFs, 0755, true);
        }

        $tmpName = $_FILES['avatar']['tmp_name'];

        // ✅ sécuriser le nom de fichier (éviter ../../ etc.)
        $originalName = basename($_FILES['avatar']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // autoriser uniquement quelques extensions
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed, true)) {
            $error = "Format d'image non autorisé (jpg, png, webp, gif).";
        } else {
            // nom unique
            $safeName = "avatar_" . $_SESSION['user_id'] . "_" . time() . "." . $ext;

            $destPath = $uploadDirFs . $safeName;

            if (move_uploaded_file($tmpName, $destPath)) {
                // ✅ on stocke un chemin relatif (comme avant)
                $newAvatar = "uploads/avatars/" . $safeName;
            } else {
                $error = "Erreur lors de l'upload de l'avatar.";
            }
        }
    }

    if (!$error) {
        $upd = $pdo->prepare("
            UPDATE users
            SET bio = :bio, avatar = :avatar
            WHERE id_user = :id
        ");

        $ok = $upd->execute([
            'bio'    => $newBio,
            'avatar' => $newAvatar,
            'id'     => $_SESSION['user_id']
        ]);

        if ($ok) {
            $success = "Profil mis à jour avec succès.";

            // recharger
            $stmt2 = $pdo->prepare("SELECT avatar, bio FROM users WHERE id_user = :id");
            $stmt2->execute(['id' => $_SESSION['user_id']]);
            $userRow = $stmt2->fetch(\PDO::FETCH_ASSOC);
        } else {
            $error = "Problème lors de la mise à jour en base.";
        }
    }
}

// Construire l'URL de l'avatar pour l'affichage
$avatarRel = $userRow['avatar'] ?? '';
$avatarRel = trim((string)$avatarRel);

$defaultAvatar = $baseUrl . "images/default-avatar.png";
if ($avatarRel === '') {
    $avatarUrl = $defaultAvatar;
} else {
    // si relatif -> baseUrl + relatif
    if ($avatarRel[0] !== '/' && !preg_match('#^https?://#i', $avatarRel)) {
        $avatarUrl = $baseUrl . $avatarRel;
    } else {
        $avatarUrl = $avatarRel;
    }
}

ob_start();
?>
<section class="profile-edit-section">
  <h2>Modifier mon profil</h2>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php elseif ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
      <div>
        <p>Avatar actuel :</p>
        <img src="<?= htmlspecialchars($avatarUrl) ?>"
             alt="Avatar" style="max-width:100px;">
      </div>

      <label for="avatar">Changer l'image de profil :</label>
      <input type="file" id="avatar" name="avatar" accept="image/*">

      <label for="bio">Bio :</label>
      <textarea id="bio" name="bio" rows="4" placeholder="Décrivez-vous..."><?= htmlspecialchars($userRow['bio'] ?? '') ?></textarea>

      <button type="submit">Enregistrer les modifications</button>
  </form>
</section>
<?php
$main  = ob_get_clean();
$titre = 'Modifier Profil';

include $racine_path . 'templates/front/header.php';
include $racine_path . 'templates/front/main.php';
include $racine_path . 'templates/front/footer.php';