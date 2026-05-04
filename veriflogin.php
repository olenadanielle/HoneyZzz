<?php  require('connect.php'); ?>
<?php  require('fonctions.php'); ?>

<?php
	$connexion = mysqli_connect("p:".SERVEUR, NOM, PASSE, BD);
	if(!$connexion){
		echo "<p>Problème : Connexion au serveur ".SERVEUR." ou à la base ".BD." impossible. <br/> Erreur : ".mysqli_error()."</p>";
	}
	
	$mailU = $_POST["mailU"];
	$mdp = $_POST["mdp"];
	
	verifieProfil($connexion, $mailU, $mdp);
?>
