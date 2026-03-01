<?php
/**
 * Page de connexion utilisateur.
 *
 * Ce fichier s'appuie sur :
 * - La session démarrée par le routeur (pas de session_start() ici).
 * - La variable $racine_path définie par le routeur ; sinon, on définit une valeur par défaut.
 */

if (!isset($racine_path)) {
    // depuis templates/front -> remonter à la racine du projet
    $racine_path = '../../';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

// Générer le token CSRF s'il n'est pas défini
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

// Inclusion des classes et fichiers nécessaires
require_once $racine_path . 'class/User.php';
require_once $racine_path . 'model/db.php';
require_once $racine_path . 'model/UserManager.php';

use App\Front\Model\FrontDB;
use App\Front\Model\UserManager;

$error = "";
$loginOk = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token invalide !");
    }

    // Récupération et nettoyage des données
    $pseudo   = trim($_POST['pseudo'] ?? '');
    $password = trim($_POST['mot_de_passe'] ?? '');

    // Connexion à la BDD
    $pdo = FrontDB::getPDO();
    $um  = new UserManager($pdo);

    // Vérification du mot de passe
    if ($um->checkPassword($pseudo, $password)) {
        $user = $um->findByPseudo($pseudo);

        if ($user) {
            // Stocke l'ID de l'utilisateur en session
            $_SESSION['user_id'] = $user->getIdUser();
            $loginOk = true;

            // Vérifie si l'utilisateur est admin
            $stmt = $pdo->prepare("SELECT 1 FROM admins WHERE id_user = :id LIMIT 1");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $isAdmin = (bool) $stmt->fetchColumn();

            // ✅ Redirection selon le rôle (baseUrl auto)
            if ($isAdmin) {
                header("Location: {$baseUrl}admin/admin-home");
                exit;
            } else {
                header("Location: {$baseUrl}home");
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
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>

            <button type="submit">Se connecter</button>
        </form>

        <p>Pas encore inscrit ?
            <a href="<?= $baseUrl ?>register">Créer un compte</a>
        </p>
    <?php else: ?>
        <p>Bienvenue, vous êtes connecté.</p>
    <?php endif; ?>
</section>
<?php
$main = ob_get_clean();
$titre = 'Connexion';

// Inclusion du squelette de page (header, main, footer)
include $racine_path . 'templates/front/header.php';
include $racine_path . 'templates/front/main.php';
include $racine_path . 'templates/front/footer.php';