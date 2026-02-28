<?php

if (!isset($racine_path)) {
    $racine_path = '../../';
}


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

/**
 * Page d'inscription...
 */
require_once($racine_path . 'class/User.php');
require_once($racine_path . 'model/db.php');
require_once($racine_path . 'model/UserManager.php');

use App\Front\Model\FrontDB;
use App\Front\Model\UserManager;
use App\Classes\User;

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token invalide !");
    }

    $pseudo   = trim($_POST['pseudo'] ?? '');
    $nom      = trim($_POST['nom'] ?? '');
    $prenom   = trim($_POST['prenom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['mot_de_passe'] ?? '');
    $d_nee    = trim($_POST['d_nee'] ?? '');

    try {
        $pdo = FrontDB::getPDO();
        $um  = new UserManager($pdo);

        
        $check = $um->findByPseudo($pseudo);
        if ($check) {
            $error = "Pseudo déjà utilisé !";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $newUser = new User(
                null,
                $pseudo,
                $nom,
                $prenom,
                $email,
                $hashed,
                date('Y-m-d H:i:s'),
                $d_nee
            );
            $ok = $um->create($newUser);
            if ($ok) {
                $success = "Inscription réussie !";
            } else {
                $error = "Problème lors de l’insertion…";
            }
        }
    } catch (\PDOException $e) {
        $error = "Erreur BDD : " . $e->getMessage();
    }
}

ob_start();
?>
<section class="register-section">
    <h2>Inscription</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <!-- Ajout du token -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <label for="d_nee">Date de naissance :</label>
        <input type="date" id="d_nee" name="d_nee" required>

        <button type="submit">S'inscrire</button>
    </form>
</section>
<?php
$main = ob_get_clean();
$titre = 'Inscription';

include($racine_path . 'templates/front/header.php');
include($racine_path . 'templates/front/main.php');
include($racine_path . 'templates/front/footer.php');
