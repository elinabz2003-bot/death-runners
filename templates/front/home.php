<?php
/**
 * Page d'accueil (profil utilisateur).
 *
 * Ce fichier affiche le profil de l'utilisateur connecté.
 * Le routeur doit avoir déjà :
 * - Démarré la session
 * - Vérifié que l'utilisateur est connecté
 * - Chargé la variable $userDetails avec les informations de l'utilisateur
 */


if (!isset($userDetails) || !is_array($userDetails) || empty($userDetails)) {
    header("Location: /monsite_exemple/login");
    exit;
}


if (!isset($racine_path)) {
    $racine_path = './';
}
?>
<section class="profile-section">
  <div class="profile-container">
      <div class="profile-header">
          <img src="<?= htmlspecialchars($userDetails['avatar'] ?? '/monsite_exemple/images/default-avatar.png') ?>" 
               alt="Avatar" class="profile-avatar">
          <div class="profile-info">
              <h2><?= htmlspecialchars($userDetails['pseudo'] ?? 'Utilisateur inconnu') ?></h2>
              <p class="bio">
                  <?= !empty($userDetails['bio']) ? htmlspecialchars($userDetails['bio']) : "Aucune bio pour le moment." ?>
              </p>
          </div>
      </div>
      <div class="profile-details">
          <p>Date d'inscription : <?= isset($userDetails['date_inscription']) ? date("d/m/Y", strtotime($userDetails['date_inscription'])) : "Non renseignée" ?></p>
          <p>Dernière connexion : <?= !empty($userDetails['derniere_connexion']) ? date("d/m/Y à H:i", strtotime($userDetails['derniere_connexion'])) : "Jamais" ?></p>
      </div>
      <div class="profile-actions">
          <!-- Liens vers les routes propres (URL rewriting) -->
          <a href="/monsite_exemple/game">
              <button class="btn-primary">🎮 Jouer</button>
          </a>
          <a href="/monsite_exemple/stats">
              <button class="btn-secondary">📊 Statistiques</button>
          </a>
          <a href="/monsite_exemple/edit-profile">
              <button class="btn-tertiary">✏️ Modifier Profil</button>
          </a>
      </div>
  </div>
</section>
