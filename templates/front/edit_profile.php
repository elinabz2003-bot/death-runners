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


if (!isset($_SESSION['user_id'])) {
    header("Location: /monsite_exemple/login");
    exit;
}


require_once $racine_path . 'model/db.php';
use App\Front\Model\FrontDB;

$pdo = FrontDB::getPDO();


$stmt = $pdo->prepare("SELECT avatar, bio FROM users WHERE id_user = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$userRow = $stmt->fetch(\PDO::FETCH_ASSOC);


if (!$userRow) {
    header("Location: ".$racine_path."control/logout.php");
    exit;
}


$error = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    $newBio = trim($_POST['bio'] ?? '');
    
    
    $newAvatar = $userRow['avatar']; 
    if (!empty($_FILES['avatar']['name'])) {
        
        $uploadDir = $racine_path . "uploads/avatars/";
        
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = $_FILES['avatar']['name'];
        $tmpName  = $_FILES['avatar']['tmp_name'];

        
        $destPath = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $destPath)) {
            
            $newAvatar = "uploads/avatars/" . $fileName;
        } else {
            $error = "Erreur lors de l'upload de l'avatar.";
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

            
            $stmt2 = $pdo->prepare("SELECT avatar, bio FROM users WHERE id_user = :id");
            $stmt2->execute(['id' => $_SESSION['user_id']]);
            $userRow = $stmt2->fetch(\PDO::FETCH_ASSOC);
        } else {
            $error = "Problème lors de la mise à jour en base.";
        }
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
      <!-- Afficher l'avatar actuel -->
      <div>
        <p>Avatar actuel :</p>
        <?php if (!empty($userRow['avatar'])): ?>
          <!-- On concatène $racine_path pour accéder physiquement au fichier 
               ou juste "/~uapv2002962/E_B_A/monsite_exemple/<?= $userRow['avatar'] ?>" 
               si on veut un lien absolu. -->
          <img src="<?= $racine_path . htmlspecialchars($userRow['avatar']) ?>" 
               alt="Avatar" style="max-width:100px;">
        <?php else: ?>
          <p>Aucun avatar</p>
        <?php endif; ?>
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


include($racine_path.'templates/front/header.php');
include($racine_path.'templates/front/main.php');
include($racine_path.'templates/front/footer.php');
