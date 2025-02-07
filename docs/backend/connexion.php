<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
//empêche PHP de modifier la structure des requêtes
ini_set('variables_order', 'EGPCS'); 
ini_set('request_order', 'GP');


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

try {
    // Connexion à PostgreSQL
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Lire les données JSON envoyées dans la requête
    $data = json_decode(file_get_contents("php://input"), true);
    error_log(print_r($data, true));
    var_dump($data);


    // Vérification des champs
    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode([
            "error" => "Données manquantes dans la requête",
            "email" => isset($data['email']) ? $data['email'] : null,
            "password" => isset($data['password']) ? 'Donnée' : 'Non donnée'
        ]);
        exit;
    }

    $email = $data['email'];
    //$password = $data['password'];
    $password = isset($data['password']) ? $data['password'] : (isset($data['motDePasse']) ? $data['motDePasse'] : null);


    // Requête sécurisée avec un paramètre préparé
    $stmt = $pdo->prepare("SELECT * FROM validation WHERE pseudo = :email");
    $stmt->execute(['email' => $email]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($resultat && password_verify($password, $resultat['passe'])) {
        session_start();
        $_SESSION['logged'] = $email;
        $_SESSION['admin'] = $resultat['admin'];
        $_SESSION['debut'] = time();

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Connexion réussie"]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "L'identifiant ou le mot de passe sont incorrects"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur lors de la connexion : " . $e->getMessage()]);
}
?>
