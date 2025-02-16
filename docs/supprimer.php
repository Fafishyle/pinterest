<html>
    La photo va être supprimée... <br>
<?php
            session_start ();
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(200);
                exit;
            }
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

            $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
                $req = $pdo->prepare("
                DELETE FROM `photo`
                WHERE `photoid` = :idphoto
                 ");
            // Exécution de la requête avec les valeurs sécurisées
            $req->execute([
                'idphoto' => $_GET['idphoto']
            ]);
?>
Photo supprimée.
<div>
        <a href="index.html" > 
            <h5><span class="titre">◄ Accueil</span></h5>
        </a>
        </div>
</html>