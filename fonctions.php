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

function afficherNbPassage($connexion,$nomRuche) {
    // 1. Requête SQL pour le nombre de passages (activité)
    $query = "SELECT nbPassage FROM MULTISENSOR M JOIN Ruche R on M.idRuche = R.idRuche
              WHERE nom = '$nomRuche' ; ";
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        // 2. Comptage pour les classes CSS
        $total = mysqli_num_rows($resultat);
        $i = 1;

        // 3. Boucle d'affichage
        while ($p = mysqli_fetch_array($resultat)) {
            
            // Gestion des classes CSS first/last
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            // Structure Card pour l'activité
            echo "<div class='card'>";
                echo "<h3>Activité (Passages)</h3>";
                
                // On affiche le nombre de passages
                echo "<p class='card-prix'>" . $p["nbPassage"] . "</p>";
                
                echo "<p class='card-label'>Mouvements détectés</p>";
                
                echo "<div class='footer-card'><small>Capteur Multisensor</small></div>";
            echo "</div>";

            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function afficherUv($connexion,$nomRuche) {
    // 1. Requête SQL pour l'indice UV
    $query = "SELECT uv FROM MULTISENSOR M JOIN Ruche R on M.idRuche = R.idRuche
              WHERE nom = '$nomRuche' ; ";
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        // 2. On compte les lignes pour gérer les classes CSS (first/last)
        $total = mysqli_num_rows($resultat);
        $i = 1;

        // 3. Boucle d'affichage
        while ($u = mysqli_fetch_array($resultat)) {
            
            // Gestion des classes CSS selon la position
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            // Structure de la carte UV
            echo "<div class='card'>";
                echo "<h3>Indice UV</h3>";
                
                // On affiche la valeur de l'indice
                echo "<p class='card-prix'>" . $u["uv"] . "</p>";
                
                echo "<p class='card-label'>Intensité du soleil</p>";
                
                // Footer de la carte pour la finition
                echo "<div class='footer-card'><small>Données Multisensor</small></div>";
            echo "</div>";

            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function afficherVibration($connexion,$nomRuche) {
    // 1. Requête SQL pour les vibrations
    $query = "SELECT vibration FROM MULTISENSOR M JOIN Ruche R on M.idRuche = R.idRuche
              WHERE nom = '$nomRuche' ;";
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        // 2. On compte les lignes pour les classes CSS
        $total = mysqli_num_rows($resultat);
        $i = 1;

        // 3. Boucle d'affichage
        while ($v = mysqli_fetch_array($resultat)) {
            
            // Gestion des classes CSS first/last
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            // Structure Card pour les vibrations
            echo "<div class='card'>";
                echo "<h3>Vibrations</h3>";
                
                // Affichage de la valeur
                echo "<p class='card-prix'>" . $v["vibration"] . "</p>";
                
                echo "<p class='card-label'>Intensité détectée</p>";
                
                echo "<div class='footer-card'><small>Capteur Multisensor</small></div>";
            echo "</div>";

            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function afficherOuvertureR($connexion,$nomRuche) {
    // Ta requête pour récupérer les ouvertures et alarmes
    $query = "Select ouvert, alarme 
From Ouverture_Ruche O join Ruche R On O.idRuche = R.idRuche 
              WHERE nom = '$nomRuche' ;";	
    
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($o = mysqli_fetch_array($resultat)) {
            // Gestion des classes CSS (first/last)
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            echo "<div class='card'>";
                // On affiche l'état d'ouverture en titre
                echo "<h3>État : " . $o["ouvert"] . "</h3>";
                
                // On affiche l'alarme avec ta classe card-label
                echo "<p class='card-label'><strong>Alarme :</strong> " . $o["alarme"] . "</p>";
                
                // Petit footer pour le style
                echo "<div class='footer-card'><small>Système de surveillance</small></div>";
            echo "</div>";
            
            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function AfficherPrise ($connexion,$nomRuche) {
    // 1. La requête SQL adaptée à votre table PRISE
    $query = "SELECT consommationW, etatPrise FROM PRISE P JOIN Ruche R on P.idRuche = R.idRuche
              WHERE nom = '$nomRuche' ;";	
    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($p = mysqli_fetch_array($resultat)) {
            // Gestion des classes CSS pour la liste
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            // Traduction de l'état (0 ou 1) en texte lisible
            $status = ($p["etatPrise"] == 1) ? "true" : "false";
            $statusClass = ($p["etatPrise"] == 1) ? "text-success" : "text-danger";

            echo "<div class='card'>";
                echo "<h3>Prise #" . $i . "</h3>";
                echo "<p class='consumption'><strong>Consommation :</strong> " . $p["consommationW"] . " W</p>";
                echo "<p><strong>État :</strong> <span class='$statusClass'>" . $status . "</span></p>";
                echo "<div class='footer-card'><small>Mise à jour en temps réel</small></div>";
            echo "</div>";
            echo "</li>";

            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function afficherBalance($connexion, $nomRuche) {
    $nomRuche = mysqli_real_escape_string($connexion, $nomRuche);

    $query = "SELECT poids, datemesure, etatBalance FROM BALANCE B JOIN Ruche R ON B.idRuche = R.idRuche WHERE nom = '$nomRuche'";

    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($b = mysqli_fetch_array($resultat)) {

            // Classes CSS
            $class = ($i == 1) ? "first" : (($i == $total) ? "last" : "");

      
            $etat = $b["etatBalance"];
            $etatClass = ($etat == "normal") ? "text-success" : "text-danger";

            echo "<li class='$class'>";
            echo "<div class='card'>";
                echo "<h3>Poids ruche</h3>";
                echo "<p class='card-prix'>" . $b["poids"] . " kg</p>";
                echo "<p><strong>État :</strong> <span class='$etatClass'>$etat</span></p>";
                echo "<div class='footer-card'><small>Date : " . $b["datemesure"] . "</small></div>";
            echo "</div>";
            echo "</li>";

            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function AfficherPanneauSolaire($connexion,$nomRuche) {
    $query = "Select etatP, puissanceW, rendement
From PanneauSolaire PS
	JOIN Batterie B on PS.idPanSol = B.idPanSol
 JOIN Ruche R on B.idRuche = R.idRuche
              WHERE nomRuche = '$nomRuche'";	

    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($ps = mysqli_fetch_array($resultat)) {
            // Gestion des classes CSS (first pour le 1er, last pour le dernier)
            if ($i == 1) { 
                echo "<li class='first'>"; 
            } elseif ($i == $total) { 
                echo "<li class='last'>"; 
            } else { 
                echo "<li>"; 
            }

            echo "<div class='card'>";
                echo "<h3>Panneau Solaire n°" . $i . "</h3>";
                echo "<p class='card-label'><strong>État :</strong> " . $ps["etatP"] . "</p>";
echo "<p><strong>Puissance :</strong> " . $ps["puissanceW"] . " W</p>"; 
echo "<p><strong>Rendement :</strong> " . $ps["rendement"] . " %</p>";
                echo "<div class='footer-card'><small>Énergie renouvelable</small></div>";
            echo "</div>";

            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function afficherTapisChauffant($connexion, $nomRuche) {
    $nomRuche = mysqli_real_escape_string($connexion, $nomRuche);
    $query = "SELECT etat_t, modeControle_t FROM TAPIS_CHAUFFANT T JOIN Ruche R ON T.idRuche = R.idRuche WHERE nom = '$nomRuche'";
  $resultat = mysqli_query($connexion, $query);
    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;
        while ($t = mysqli_fetch_array($resultat)) {
            $class = ($i == 1) ? "first" : (($i == $total) ? "last" : "");

            $etat = ($t["etat_t"] == 1) ? "ON" : "OFF";
            $etatClass = ($t["etat_t"] == 1) ? "text-success" : "text-danger";

            switch ($t["modeControle_t"]) {
                case "auto":
                    $mode = "Automatique";
                    break;
                case "on":
                    $mode = "Forcé ON";
                    break;
                case "off":
                    $mode = "Arrêt";
                    break;
                default:
                    $mode = "Inconnu";
            }
            echo "<li class='$class'>";
            echo "<div class='card'>";
                echo "<h3>Tapis Chauffant</h3>";
                echo "<p><strong>État :</strong> <span class='$etatClass'>$etat</span></p>";
                echo "<p><strong>Mode :</strong> $mode</p>";
                echo "<div class='footer-card'><small>Mesure n°$i</small></div>";
            echo "</div>";
            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function AfficherVentilation($connexion, $nomRuche) {
    // On nettoie le nom de la ruche pour la sécurité
    $nomRuche = mysqli_real_escape_string($connexion, $nomRuche);

    // Requête : on filtre par ruche et on trie par ID (ou date) décroissant
    // On peut ajouter "LIMIT 10" pour n'afficher que les 10 dernières mesures
    $query = "SELECT  etat_v, modeControle_v FROM Multisensor M JOIN Ruche R on M.idRuche = R.idRuche
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
                echo "<h3>Etat Ventilation</h3>";
                // Affichage du taux
                echo "<p class='card-prix'>" . $h["etat_v"] . " </p>";
                echo "<h3>Mode contrôle</h3>";
*


                // Affichage du taux
                echo "<p class='card-prix'>" . $h[" modeControle_v"] . " </p>";
                echo "<div class='footer-card'><small>Mesure n°" . $i . "</small></div>";
            echo "</div>";
            echo "</li>";
            $i++;
        }
    } else {
        echo "Erreur SQL : " . mysqli_error($connexion);
    }
}

function AfficherBatterie($connexion, $nomRuche) {

    $nomRuche = mysqli_real_escape_string($connexion, $nomRuche);

    $query = "SELECT etatB, niveauCharge 
              FROM BATTERIE B
              JOIN RUCHE R ON B.idRuche = R.idRuche
              WHERE R.nom = '$nomRuche'";

    $resultat = mysqli_query($connexion, $query);

    if ($resultat) {
        $total = mysqli_num_rows($resultat);
        $i = 1;

        while ($h = mysqli_fetch_array($resultat)) {

            $class = "";
            if ($i == 1) $class = "first";
            elseif ($i == $total) $class = "last";

            echo "<li class='$class'>";
            echo "<div class='card'>";
                echo "<h3>Etat Batterie</h3>";
                echo "<p class='card-prix'>" . $h["etatB"] . "</p>";

                echo "<h3>Niveau de charge</h3>";
                echo "<p class='card-prix'>" . $h["niveauCharge"] . " %</p>";

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
