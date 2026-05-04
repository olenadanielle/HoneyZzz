<?php require('navbar.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="style.css" rel="stylesheet">
  <title>Connexion – Ruche Connectée</title>
</head>
<body>

<!--Connexion-->
<section class="connexion" id="connexion">
  <div class="connexion-card">
    <h2>Connexion</h2>
    <p class="connexion-sub">Accédez à votre espace apiculteur</p>

    <?php 
		// on remet à zero la session
		if(!isset($_SESSION)){
    		session_start();
		}
		$_SESSION['mailU']='';?>

    <form action="veriflogin.php" method="POST">
    <div class="form-group">
      <label for="email">Adresse e-mail</label>
      <input type="email" id="email" name="mailU" placeholder="vous@exemple.com" />
    </div>

    <div class="form-group">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="mdp" placeholder="••••••••" />
    </div>

    <div class="form-options">
      <label class="checkbox-wrap">
        <input type="checkbox" id="remember"> Se souvenir de moi
      </label>
      <a href="#" class="forgot-link">Mot de passe oublié ?</a>
    </div>

    <button class="btn-decouvrir" type="submit" style="width:100%; text-align:center;">Se connecter</button>
    <p> <?php if(isset($_SESSION['message'])) echo $_SESSION['message'];?></p>
    </form>
    <p class="register-link">Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
  </div>
</section>

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
</html>