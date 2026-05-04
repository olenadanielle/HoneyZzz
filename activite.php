<?php  require('connect.php'); ?>
<?php  require('fonctions.php'); ?>
<?php
session_start();

// 1. Sécurité
if (empty($_SESSION['mailU'])) {
    header("Location: connexion.php");
    exit();
}

// 2. Récupération du nom de la ruche
$nomRuche = isset($_GET['nom']) ? $_GET['nom'] : "Ruche inconnue";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HoneyZzz - Activité <?php echo $nomRuche; ?></title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="navgauche">
    
    <nav class="sidebar">
        <a href="temperature.php?nom=<?php echo $nomRuche; ?>" class="nav-item">Température</a>
        
        <a href="humidite.php?nom=<?php echo $nomRuche; ?>" class="nav-item">Humidité</a>
        
        <a href="poids.php?nom=<?php echo $nomRuche; ?>" class="nav-item">Poids</a>
        
        <a href="activite.php?nom=<?php echo $nomRuche; ?>" class="nav-item">Activité</a>
        
        <a href="consommation.php?nom=<?php echo $nomRuche; ?>" class="nav-item">Consommation</a>
        
        <a href="controleruche.php?nom=<?php echo $nomRuche; ?>" class="nav-item active">Contrôle</a>

        <a href="apiculteur.php" class="btn-retour">Retour</a>
    </nav>
</div>
</body>