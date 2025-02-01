<?php
    header('Content-Type: application/json');
    // Je me connecte à la base de données.

    $con = mysqli_connect('localhost', 'root', '') or die (mysqli_error());
        mysqli_select_db($con,'bdd') or die (mysqli_error($con));

        /*
        // pour l'affichage des valeurs dans le champ
        if( isset($_POST['email']) )
        {
        echo "Vous avez insérer ". $_POST['email'] ."  <br>";    }
        if(isset($_POST['password']))
        {
        echo  $_POST['password'] ."<br>";
        }
        */
        

    //dès que le champ pass et pass2 sont remplis
    //et pour ne pas voir afficher le message d'erreur: /!\ Undefined array key "pass"
    if(isset($_POST['email']) && isset($_POST['password']))
    {
        // Je mets aussi certaines sécurités pour l'envoie du mot de passe à la BDD ici…
        $pass = mysqli_real_escape_string($con, $_POST['password']);
        // Je vais crypter le mot de passe.
        $pass = sha1($pass);
            // Si le mot de passe est confirmé
            //et qu'il clique sur submit 
            
            $pseudo =  mysqli_real_escape_string($con, $_POST['email']);   
            //Je me reconnecte à nouveau      
            $con = new PDO("mysql:host=localhost; dbname=bdd", "root", "");               
            $req = $con->query("SELECT * FROM validation WHERE pseudo = '". $pseudo ."' ");
            echo "En cours de recherche.";

            //je déclare une chaine de caractère html
            // un attribut d'un champ pour le cacher le message mot de passe et pseudo non corrects
            $hide= " ";
            while ($resultat = $req->fetch() )
            {
                echo '.';
                if ($resultat['pseudo']== $pseudo && $resultat['passe']== $pass)
                {
                    echo "Le pseudo est confirmé. <br>";
                    echo "Le mot de passe est confirmé. <br>";
                    echo " Connexion!<br>";
                    $hide= "style='display: none'";
                    //ouverture de session
                    session_start ();
                    $_SESSION['logged'] = $_POST['email'];
                    //0 si utilisateur simple, 1 si administrateur
                    $_SESSION['admin'] = $resultat['admin'];
                    echo " ADMINISTRATEUR? ". $_SESSION['admin'];
                    //temps de début de session
                    $_SESSION['debut'] = time();
                    //ça va ouvrir index.html mais en mode utilisateur
                    header ('location: index.html');
                    break;
                }  
            }
            //s'affiche si erreur, sinon le champ est caché
            echo "<div $hide >Le pseudo ou le mot de passe ne sont pas corrects, réessayez. </div>";
    }
    ?>