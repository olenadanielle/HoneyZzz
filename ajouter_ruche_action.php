<?php  require('include/connect.php'); ?>
<?php  require('include/fonctions.php'); ?>

<?php
// On inclut la connexion et tes fonctions
$connexion = mysqli_connect(SERVEUR, NOM, PASSE, BD);

// On récupère les données envoyées par le formulaire via $_POST
// ATTENTION : les noms entre crochets doivent être les mêmes que les "name" de ton HTML
$nom = $_POST['nom']; 
$date = $_POST['dateInstallation'];
$espece = $_POST['especeAbeille'];
$statut = $_POST['statut'];
$idEmplacement = $_POST['idEmplacement'];


// On appelle ta fonction InscriptionUtilisateur
$resultat = AjouterRuche($connexion, $nom, $date, $espece, $statut, $idEmplacement);

if ($resultat) {
    // Si ça marche, on retourne à la liste des ruches
    header("Location: apiculteur.php?success=1");
} else {
    // Si ça plante, on affiche l'erreur
    echo "Erreur lors de l'ajout de la ruche : " . mysqli_error($connexion);
}
?>