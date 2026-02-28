<?php
/**
 * Page de contact du site.
 *
 * Ce script affiche :
 * - Une section d'introduction et de réseaux sociaux.
 * - Un formulaire de contact permettant aux utilisateurs d'envoyer un message.
 * - Les messages d'erreur ou de succès stockés temporairement dans $_SESSION.
 *
 * Le traitement du formulaire est géré dans `process_contact.php`.
 *
 * @uses $_SESSION['contact_error'] pour afficher une erreur
 * @uses $_SESSION['contact_success'] pour afficher un message de confirmation
 * @return void
 */


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($racine_path)) {
    $racine_path = '../../';
}


require_once($racine_path . 'model/db.php');


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];


ob_start();
?>
<main>
  <section class="contact-section">
    <div class="contact-header">
      <h1>Contactez-nous</h1>
      <p>
        Pour toute réclamation ou question, utilisez le formulaire ci-dessous 
        ou rejoignez-nous sur nos réseaux sociaux.
      </p>
    </div>

    <div class="contact-social">
      <h2>Nos Réseaux Sociaux</h2>
      <ul>
        <li>
          <a href="https:
            <i class="fab fa-facebook-f"></i> Facebook
          </a>
        </li>
        <li>
          <a href="https:
            <i class="fab fa-instagram"></i> Instagram
          </a>
        </li>
        <li>
          <a href="https:
            <i class="fab fa-twitter"></i> Twitter
          </a>
        </li>
      </ul>
    </div>

    <?php
      
      if (!empty($_SESSION['contact_error'])) {
          echo '<p style="color: red;">' . $_SESSION['contact_error'] . '</p>';
          unset($_SESSION['contact_error']);
      }
      if (!empty($_SESSION['contact_success'])) {
          echo '<p style="color: #00ffcc;">' . $_SESSION['contact_success'] . '</p>';
          unset($_SESSION['contact_success']);
      }
    ?>

    <!-- Formulaire de contact -->
    <form action="<?= $racine_path ?>templates/front/process_contact.php" method="POST">
      <!-- Champ caché pour le token CSRF -->
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

      <label for="name">Nom :</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required>

      <label for="subject">Sujet :</label>
      <input type="text" id="subject" name="subject" required>

      <label for="message">Message :</label>
      <textarea id="message" name="message" rows="5" required></textarea>

      <button type="submit">Envoyer</button>
    </form>
  </section>
</main>
<?php
$main  = ob_get_clean();
$titre = 'Contact';


include($racine_path.'templates/front/header.php');
include($racine_path.'templates/front/main.php');
include($racine_path.'templates/front/footer.php');
