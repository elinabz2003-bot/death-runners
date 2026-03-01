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

// ✅ Base URL auto (local: /monsite_exemple/, prod: /)
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($basePath === '') {
    $basePath = '/';
}
$baseUrl = ($basePath === '/' ? '/' : $basePath . '/');

// Vérifier que $userDetails est défini et non vide, sinon rediriger vers la connexion
if (!isset($userDetails) || !is_array($userDetails) || empty($userDetails)) {
    header("Location: {$baseUrl}login");
    exit;
}

// Définir $racine_path pour les inclusions si non défini
if (!isset($racine_path)) {
    $racine_path = './';
}

// Avatar par défaut (chemin public)
$defaultAvatar = $baseUrl . 'images/default-avatar.png';

// Avatar utilisateur (si vide -> défaut)
$avatar = $userDetails['avatar'] ?? '';
$avatar = trim((string)$avatar);
if ($avatar === '') {
    $avatar = $defaultAvatar;
} else {
    // Si l'avatar est stocké comme chemin relatif (ex: "uploads/avatars/x.webp"),
    // on le rend accessible via la baseUrl.
    if ($avatar[0] !== '/' && !preg_match('#^https?://#i', $avatar)) {
        $avatar = $baseUrl . $avatar;
    }
}
?>
<section class="profile-section">
  <div class="profile-container">
      <div class="profile-header">
          <img src="<?= htmlspecialchars($avatar) ?>"
               alt="Avatar" class="profile-avatar">

          <div class="profile-info">
              <h2><?= htmlspecialchars($userDetails['pseudo'] ?? 'Utilisateur inconnu') ?></h2>
              <p class="bio">
                  <?= !empty($userDetails['bio']) ? htmlspecialchars($userDetails['bio']) : "Aucune bio pour le moment." ?>
              </p>
          </div>
      </div>

      <div class="profile-details">
          <p>Date d'inscription :
              <?= isset($userDetails['date_inscription']) ? date("d/m/Y", strtotime($userDetails['date_inscription'])) : "Non renseignée" ?>
          </p>
          <p>Dernière connexion :
              <?= !empty($userDetails['derniere_connexion']) ? date("d/m/Y à H:i", strtotime($userDetails['derniere_connexion'])) : "Jamais" ?>
          </p>
      </div>

      <div class="profile-actions">
          <a href="<?= $baseUrl ?>game">
              <button class="btn-primary">🎮 Jouer</button>
          </a>
          <a href="<?= $baseUrl ?>stats">
              <button class="btn-secondary">📊 Statistiques</button>
          </a>
          <a href="<?= $baseUrl ?>edit-profile">
              <button class="btn-tertiary">✏️ Modifier Profil</button>
          </a>
      </div>
  </div>
</section>