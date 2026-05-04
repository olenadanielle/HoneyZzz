<?php  require('connect.php'); ?>
<?php  require('fonctions.php'); ?>

<?php
// On inclut la connexion et tes fonctions
$connexion = mysqli_connect(SERVEUR, NOM, PASSE, BD);

// On récupère les données envoyées par le formulaire via $_POST
// ATTENTION : les noms entre crochets doivent être les mêmes que les "name" de ton HTML
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email']; // name="email" dans ton formulaire
$tel = $_POST['tel'];     // name="tel"
$mdp = $_POST['mdp'];     // name="mdp"
$role = $_POST['role'];   // name="role"

// Optionnel : on vérifie que les deux mots de passe correspondent
if ($_POST['mdp'] != $_POST['confirm-mdp']) {
    echo "Erreur : Les mots de passe ne correspondent pas.";
    exit();
}

// On appelle ta fonction InscriptionUtilisateur
$resultat = InscriptionUtilisateur($connexion, $nom, $prenom, $email,$tel, $mdp, $role);

if ($resultat) {
    // Si l'insertion a marché, on redirige vers la page de connexion
    header("Location: connexion.php?inscription=success");
} else {
    // Si ça a échoué, on affiche l'erreur SQL
    echo "Erreur : Cette adresse email est déjà utilisée ou les données sont invalides" . mysqli_error($connexion);
}