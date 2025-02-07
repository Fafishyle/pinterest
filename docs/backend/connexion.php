<?php
    // Pour communiquer avec le même navigateur
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    // Récupérer l'URL de la base de données depuis les variables d'environnement Heroku
    $DATABASE_URL = getenv('DATABASE_URL');

    if (!$DATABASE_URL) {
        die(json_encode(["error" => "DATABASE_URL non définie."]));
    }

    // Décomposer l'URL en ses parties
    $parts = parse_url($DATABASE_URL);
    $host = $parts["host"];
    $user = $parts["user"];
    $pass = $parts["pass"];
    $port = $parts["port"];
    $dbname = ltrim($parts["path"], "/");
    // Je me connecte à la base de données.

    try {
        // Connexion à PostgreSQL
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Lire les données JSON envoyées dans la requête
        $data = json_decode(file_get_contents("php://input"), true);

        $data_raw = file_get_contents("php://input");
        error_log("Données reçues : " . $data_raw);


        // Vérification si toutes les données nécessaires sont envoyées via POST
        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode([
                "email" => isset($data['email']) ? $data['email'] : null,
                "password" => isset($data['password']) ? $data['password'] : null,
                "error" => "Données manquantes dans la requête"
            ]);
            exit;
        }

        $email = $data['email'];
        $password = $data['password'];
            

        //si les 2 champs sont remplies
        if(isset($data['email']) && isset($data['password']))

        {
            // Hashage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                // Si le mot de passe est confirmé          
                $stmt = $pdo->prepare("SELECT * FROM validation WHERE pseudo = '". $pseudo ."' ");
                echo "En cours de recherche.";

                //je déclare une chaine de caractère html
                // un attribut d'un champ pour le cacher le message mot de passe et pseudo non corrects
                $hide= " ";
                while ($resultat = $stmt->fetch() )
                {
                    echo '.';
                    if ($resultat['pseudo']== $pseudo && $resultat['passe']== $pass)
                    {
                        echo "Le pseudo est confirmé. <br>";
                        echo "Le mot de passe est confirmé. <br>";
                        echo " Connexion!<br>";
                        $hide= "style='display: none'";
                        http_response_code(201);
                        echo json_encode(["message" => "Connexion réussie."]);
                        //ouverture de session
                        session_start ();
                        $_SESSION['logged'] = $_POST['email'];
                        //0 si utilisateur simple, 1 si administrateur
                        $_SESSION['admin'] = $resultat['admin'];
                        echo " ADMINISTRATEUR? ". $_SESSION['admin'];
                        //temps de début de session
                        $_SESSION['debut'] = time();
                        //ça va ouvrir index.html mais en mode utilisateur
                        header ('location: index.php');
                        break;
                    }  
                }
                //s'affiche si erreur, sinon le champ est caché
                http_response_code(500);
                echo json_encode(["error" => "L'identifiant ou le mot de passe ne sont pas corrects, réessayez. " . $e->getMessage()]);
                echo "<div $hide >L'identifiant ou le mot de passe ne sont pas corrects, réessayez. </div>";
        }
    }catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors de la connexion" . $e->getMessage()]);
    }
    ?>