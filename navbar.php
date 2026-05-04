<?php
// Démarrer la session si elle n'existe pas
if(!isset($_SESSION)){
    session_start();
}

// Vérifier si l'utilisateur est connecté
$isConnected = !empty($_SESSION['mailU']);
$userEmail = isset($_SESSION['mailU']) ? $_SESSION['mailU'] : '';
?>

<!--Barre de navigation-->
<nav>
  <div class="nav-left">
    <ul class="nav-links">
      <li><a href="index.php" class="active">Accueil</a></li>
      <li><a href="boutique.php">Boutique</a></li>
    </ul>
  </div>

  <div class="nav-center">
    <img src="logo_honeyzzz2.png" alt="Logo Ruche Connectée" class="logo-img">
  </div>

  <div class="nav-right">
    <?php if ($isConnected): ?>
      <!-- Menu pour utilisateur connecté -->
      <button class="btn-panier">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Panier
      </button>

      <!-- Profil avec dropdown -->
      <div class="profile-menu" id="profile-menu">
        <button class="btn-profil" id="btn-profil-toggle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
          </svg>
          Profil
        </button>
        <div class="profile-dropdown" id="profile-dropdown">
          <p class="profile-email"><?php echo htmlspecialchars($userEmail); ?></p>
          <hr>
          <a href="apiculteur.php">Mon profil</a>
          <a href="deconnexion.php" class="btn-logout">Déconnexion</a>
        </div>
      </div>

      <script>
        document.getElementById('btn-profil-toggle').addEventListener('click', function(e) {
          e.stopPropagation();
          document.getElementById('profile-menu').classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
          const profileMenu = document.getElementById('profile-menu');
          if (!profileMenu.contains(e.target)) {
            profileMenu.classList.remove('active');
          }
        });
      </script>
    <?php else: ?>
      <!-- Menu pour utilisateur non connecté -->
      <a href="connexion.php" class="btn-connexion active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="8" r="4"/>
          <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
        </svg>
        Connexion
      </a>

      <button class="btn-panier">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Panier
      </button>
    <?php endif; ?>
  </div>
</nav>
