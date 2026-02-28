<?php
/**
 * Page de connexion utilisateur.
 *
 * Ce fichier s'appuie sur :
 * - La session démarrée par le routeur (pas de session_start() ici).
 * - La variable $racine_path définie par le routeur ; sinon, on définit une valeur par défaut.
 */


if (!isset($racine_path)) {
    $racine_path = '../../';
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];


require_once $racine_path . 'class/User.php';
require_once $racine_path . 'model/db.php';       
require_once $racine_path . 'model/UserManager.php';

use App\Front\Model\FrontDB;
use App\Front\Model\UserManager;
use App\Classes\User;

$error = "";
$loginOk = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token invalide !");
    }

    
    $pseudo   = trim($_POST['pseudo'] ?? '');
    $password = trim($_POST['mot_de_passe'] ?? '');

    
    $pdo = FrontDB::getPDO();
    $um  = new UserManager($pdo);

    
    if ($um->checkPassword($pseudo, $password)) {
        $user = $um->findByPseudo($pseudo);
        if ($user) {
            
            $_SESSION['user_id'] = $user->getIdUser();
            $loginOk = true;

            
            $stmt = $pdo->prepare("SELECT 1 FROM admins WHERE id_user = :id LIMIT 1");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $isAdmin = (bool) $stmt->fetchColumn();

            
            if ($isAdmin) {
                header("Location: /monsite_exemple/admin/admin-home");
                exit;
            } else {
                header("Location: /monsite_exemple/home");
                exit;
            }
        } else {
            $error = "Impossible de récupérer l'utilisateur en base.";
        }
    } else {
        $error = "Pseudo ou mot de passe incorrect !";
    }
}

ob_start();
?>
<section class="login-section">
    <h2>Connexion</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!$loginOk): ?>
    <form action="" method="POST">
        <!-- Champ caché pour le token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">
        
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore inscrit ? 
       <a href="/monsite_exemple/register">Créer un compte</a>
    </p>
    <?php else: ?>
        <p>Bienvenue, vous êtes connecté.</p>
    <?php endif; ?>
</section>
<?php
$main = ob_get_clean();
$titre = 'Connexion';


include($racine_path . 'templates/front/header.php');
include($racine_path . 'templates/front/main.php');
include($racine_path . 'templates/front/footer.php');
