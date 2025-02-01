<?php
    header('Content-Type: application/json');
    // Je me connecte à la base de données.

    // Récupérer l'URL de la base de données depuis les variables d'environnement Heroku
    $DATABASE_URL = getenv('postgres://uc6roibm5k3en1:p3939911c6d834d6448c9fbb4897aea2ee286bd2572d7999e0f8f36fe5753849e@c8m0261h0c7idk.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com:5432/d6m1ssvjvankel'
    );

    if (!$DATABASE_URL) {
        die("Erreur: DATABASE_URL non définie.");
    }

    // Décomposer l'URL en ses parties
    $parts = parse_url($DATABASE_URL);
    $host = $parts["host"];
    $user = $parts["user"];
    $pass = $parts["pass"];
    $port = $parts["port"];
    $dbname = ltrim($parts["path"], "/");


    // Connexion à PostgreSQL
    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        echo "Connexion réussie à la base de données.";
    } catch (PDOException $e) {
        die("Erreur de connexion: " . $e->getMessage());
    }


    
    $con = mysqli_connect('localhost', 'root', '') or die (mysqli_error());
        mysqli_select_db($con,'bdd') or die (mysqli_error($con));

        /* pour l'affichage des valeurs dans le champ
        if( isset($_POST['email']) )
        {
        echo "Vous avez insérer ". $_POST['email'] ."  <br>";    }
        if(isset($_POST['password']))
        {
        echo  $_POST['password'] ."<br>";
        }
        if(isset($_POST['confirmation']))
        {echo $_POST['confirmation']. "<br>";}
        */

    //dès que le champ pass et pass2 sont remplis
    //et pour ne pas voir afficher le message d'erreur: /!\ Undefined array key "pass"
    if(isset($_POST['submit']) && isset($_POST['password']) && isset($_POST['confirmation']))
    {
        // Je mets aussi certaines sécurités pour l'envoie du mot de passe à la BDD ici…
        $pass = mysqli_real_escape_string($con, $_POST['password']);
        $pass2 = mysqli_real_escape_string($con,$_POST['confirmation']);
        if($_POST['password'] !== $_POST['confirmation'])
        {
            echo 'Les deux mots de passe que vous avez rentrés ne correspondent pas… réessayez';
        }
        else
        {
            // Si le mot de passe est confirmé
            //et qu'il clique sur submit 
            echo "Le mot de passe est confirmé. <br>";
            $pseudo =  mysqli_real_escape_string($con, $_POST['email']);   
            //Je me reconnecte à nouveau      
            $con = new PDO("mysql:host=localhost; dbname=bdd", "root", "");               
                $req = $con->query("SELECT count(*) FROM validation WHERE pseudo = '". $pseudo ."' ");
                $resultat= $req->fetch();
                echo "Il y a ". $resultat['count(*)']." correspondant à ce pseudo. <br>";
                if ($resultat['count(*)']== 0)
                {
                    echo "Le pseudo est confirmé. <br>";
                    // Je vais crypter le mot de passe.
                    $pass = sha1($pass);
                    $con->query("INSERT INTO validation VALUES('', '$pseudo', '$pass', 0)");
                    echo " Inscription terminée!<br>";
                }else{
                    echo "Le pseudo est déjà utilisé... , veuillez changer de pseudo";
                    }
        }    
    }
    ?>