<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="style.css" rel="stylesheet">
  <title>Inscription – Ruche Connectée</title>
</head>
<body>

<?php require('navbar.php'); ?>

<!--Inscription-->
<form class="connexion" id="inscription" action="inscription_action.php" method="POST">
  <div class="connexion-card">
    <h2>Créer un compte</h2>
    <p class="connexion-sub">Rejoignez la communauté HoneyZzz</p>
    <div class="form-group">
      <label>Je suis un :</label>
      <div class="role-choice">
        <label class="role-option">
          <input type="radio" name="role" value="client" checked>
          <span>🛒 Client</span>
        </label>
        <label class="role-option">
          <input type="radio" name="role" value="apiculteur">
          <span>🐝 Apiculteur</span>
        </label>
      </div>
    </div>
    <div class="form-group">
      <label for="prenom">Prénom</label>
      <input type="text" name="prenom" id="prenom" placeholder="Jean" />
    </div>

    <div class="form-group">
      <label for="nom">Nom</label>
      <input type="text" name="nom" id="nom" placeholder="Dupont" />
    </div>

    <div class="form-group">
      <label for="email">Adresse e-mail</label>
      <input type="email" name="email" id="email" placeholder="vous@exemple.com" />
    </div>

    <div class ="form-group">
      <label for="tel">Numéro de téléphone</label>
      <input type="tel" name="tel" id="tel" placeholder="06 00 00 00 00" />
    </div>

    <div class="form-group">
      <label for="password">Mot de passe</label>
      <input type="password" name="mdp" id="password" placeholder="••••••••" />
    </div>

    <div class="form-group">
      <label for="confirm-password">Confirmer le mot de passe</label>
      <input type="password" name="confirm-mdp" id="confirm-password" placeholder="••••••••" />
    </div>

    <div class="form-options">
      <label class="checkbox-wrap">
        <input type="checkbox" id="cgu"> J'accepte les <a href="#" class="forgot-link">conditions d'utilisation</a>
      </label>
    </div>

    <button type="submit" class="btn-decouvrir" style="width:100%; text-align:center;">
      Créer mon compte
    </button>

    <p class="register-link">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
  </div>
</form>

</body>

<footer>
  <div class="footer-content">
    <h5 class="footer-title">Nous suivre sur :</h5>
    <div class="social-links">
      <a href="https://www.instagram.com" target="_blank" class="social-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
          <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
          <path d="m16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
          <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
        </svg>
      </a>
      <a href="https://www.facebook.com" target="_blank" class="social-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
          <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
        </svg>
      </a>
      <a href="https://www.twitter.com" target="_blank" class="social-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
          <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
        </svg>
      </a>
    </div>
    <p class="footer-copyright">&copy; 2024 HoneyZzz. Tous droits réservés.</p>
  </div>
</footer>

<script src="script.js"></script>
<!-- Modale non connecté -->
<div class="modale-overlay" id="modale" style="display:none;">
  <div class="modale-box">
    <p>⚠️ Vous n'êtes pas connecté.</p>
    <button class="btn-decouvrir" onclick="fermerModale()">Fermer</button>
  </div>
</div>
</body>
</html>
