<html>
    La photo va être supprimée... <br>
    <style> 
     .alert {
			padding: 20px;
			background-color: #D8BFD8; 
			color: #8A2BE2;
			margin-bottom: 15px;
				}
    </style>
<?php
            session_start();
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(200);
                exit;
            }
            
            // Récupérer l'URL de la base de données depuis Heroku
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
            
            try {
                // Connexion à PostgreSQL
                $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            
                // Vérifier si `idphoto` est présent et valide
                if (!isset($_GET['idphoto']) || !ctype_digit($_GET['idphoto'])) {
                    die(json_encode(["error" => "ID photo invalide."]));
                }
            
                $idphoto = (int) $_GET['idphoto']; // Convertir en entier
            
                // Suppression sécurisée avec PostgreSQL (pas de backticks ` `)
                $req = $pdo->prepare("DELETE FROM photo WHERE photoid = :idphoto");
            
                // Exécution de la requête
                $req->execute(['idphoto' => $idphoto]);
            
                if ($req->rowCount() > 0) {
                    echo json_encode(["success" => "Photo supprimée avec succès."]);
                } else {
                    echo json_encode(["error" => "Aucune photo trouvée avec cet ID."]);
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
            }
            
?>
<div class='alert'>
    Photo supprimée
</div>
</html>

<script>
setTimeout(() => {
            window.location.href = 'index.php'; // Redirection après fermeture
        }, 2000);
</script>