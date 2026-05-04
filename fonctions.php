<?php

function verifieProfil($connexion, $mailU, $mdp){
	
	$query = "select role from Utilisateur where mailU ='".$mailU."' and mdp='".$mdp."'";
	$resultat =  mysqli_query($connexion,$query);
	
	if ($resultat) {
        // mysqli_num_rows permet de savoir si on a trouvé 1 utilisateur
        if (mysqli_num_rows($resultat) == 1) {
            $user = mysqli_fetch_array($resultat);
            $role = $user['role'];

            session_start();
            $_SESSION['mailU'] = $mailU;
            $_SESSION['role'] = $role; // On stocke le rôle en session
            $_SESSION['message'] = '';

            // LOGIQUE DE REDIRECTION SELON LE RÔLE
            if ($role == 'apiculteur') {
                header("Location: apiculteur.php"); // Page de gestion
            } 
            elseif ($role == 'client') {
                header("Location: boutique.php"); // Page d'achat
            } 
            else {
                header("Location: index.php"); // Admin ou autre
            }
            exit();
        } else {
            session_start();
            $_SESSION['message'] = 'Login ou mot de passe incorrect';
            header("Location: connexion.php");
            exit();
        }
     }   
     else
      {
        echo "<p>Erreur dans l'exécution de la requête.<br>";
        echo "Message du serveur de base de données : ".mysqli_error($connexion);

      }
}

function InscriptionUtilisateur($connexion, $nom, $prenom, $email,$tel, $mdp, $role) {
    // Nettoyage des données
    $nom = mysqli_real_escape_string($connexion, $nom);
    $prenom = mysqli_real_escape_string($connexion, $prenom);
    $email = mysqli_real_escape_string($connexion, $email);
    $tel = mysqli_real_escape_string($connexion, $tel);
    $mdp = mysqli_real_escape_string($connexion, $mdp);
    $role = mysqli_real_escape_string($connexion, $role);

    // On récupère la date du jour pour 'datecreation'
    $date = date("Y-m-d");

    //vérification que l'email n'existe pas déjà
    $check = mysqli_query($connexion, "SELECT mailU FROM Utilisateur WHERE mailU = '$email'");
    if(mysqli_num_rows($check) > 0) {
        return false; // L'email existe déjà, on arrête tout
    }

    // Requête SQL avec TES noms de colonnes : mdp et role
    $sql = "INSERT INTO Utilisateur (nomU, prenomU, mailU,telU, mdp, role, datecreation) 
            VALUES ('$nom', '$prenom', '$email', '$tel', '$mdp', '$role', '$date')";

    return mysqli_query($connexion, $sql);
}
function AfficherProduit($connexion) {
    // Ta requête avec tes données exactes
    $query = "select nomProduit, prix, dateRecolte, format from Produit";	
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        // On récupère le nombre total pour gérer les classes CSS
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($p = mysqli_fetch_array($resultat)) {
            // Gestion simplifiée des classes (first pour le 1er, last pour le dernier)
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            echo "<div class='card'>";
                echo "<h3>" . $p["nomProduit"] . "</h3>";
                echo "<p class='price'>" . $p["prix"] . " €</p>";
                echo "<p><strong>Format :</strong> " . $p["format"] . "</p>";
                echo "<div class='footer-card'><small>Récolte : " . $p["dateRecolte"] . "</small></div>";
            echo "</div>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function AfficherRuche($connexion, $mailU) {
    $query = "SELECT R.nom, R.dateInstallation, R.especeAbeille, R.statut, E.nomLieu, E.adresse 
              FROM Ruche R 
              JOIN Emplacement E ON R.idEmplacement = E.idEmplacement 
              JOIN Utilisateur U ON E.idUtilisateur = U.idUtilisateur 
              WHERE U.mailU = '$mailU'";

    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($r = mysqli_fetch_array($resultat)) {
            // Gestion des classes first/last comme pour les produits
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            // Structure simple avec tes classes CSS
            $lien = "controleruche.php?nom=" . urlencode($r['nom']);

            echo "  <a href='$lien' class='card' style='text-decoration:none; color:inherit;'>";
            echo "    <h3>" . $r["nom"] . "</h3>";
            echo "    <p class='card-label'>" . $r["especeAbeille"] . "</p>";
            echo "    <p><strong>Lieu :</strong> " . $r["nomLieu"] . "</p>";
            echo "    <div class='footer-card'><small>Installée le : " . $r["dateInstallation"] . "</small></div>";
            echo "  </a>";
            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function AjouterRuche($connexion, $nom, $date, $espece, $statut, $idEmplacement) {
    // Sécurité : on nettoie les chaînes pour éviter les injections SQL
    $nom = mysqli_real_escape_string($connexion, $nom);
    $date = mysqli_real_escape_string($connexion, $date);
    $espece = mysqli_real_escape_string($connexion, $espece);
    $statut = mysqli_real_escape_string($connexion, $statut);
    $idEmplacement = (int)$idEmplacement; // On force en nombre entier

    // Requête SQL d'insertion
    $sql = "INSERT INTO Ruche (nom, dateInstallation, especeAbeille, statut, idEmplacement) 
            VALUES ('$nom', '$date', '$espece', '$statut', '$idEmplacement')";

    return mysqli_query($connexion, $sql);
}


function afficherHumidite($connexion, $nomRuche) {
    // On nettoie le nom de la ruche pour la sécurité
    $nomRuche = mysqli_real_escape_string($connexion, $nomRuche);

    // Requête : on filtre par ruche et on trie par ID (ou date) décroissant
    // On peut ajouter "LIMIT 10" pour n'afficher que les 10 dernières mesures
    $query = "SELECT humidite FROM Multisensor M JOIN Ruche R on M.idRuche = R.idRuche
              WHERE nom = '$nomRuche'"; 
    
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($h = mysqli_fetch_array($resultat)) {
            // Gestion des classes CSS
            $class = "";
            if ($i == 1) $class = "first";
            elseif ($i == $total) $class = "last";

            echo "<li class='$class'>";
            echo "<div class='card'>";
                echo "<h3>Humidité</h3>";
                // Affichage du taux
                echo "<p class='card-prix'>" . $h["humidite"] . " %</p>";
                echo "<div class='footer-card'><small>Mesure n°" . $i . "</small></div>";
            echo "</div>";
            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}


?>