<?php
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

try {
    // Connexion à PostgreSQL
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Lire les données JSON envoyées dans la requête
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérification si toutes les données nécessaires sont envoyées via POST
    if (!isset($data['email']) || !isset($data['password']) || !isset($data['confirmation'])) {
        echo json_encode([
            "email" => isset($data['email']) ? $data['email'] : null,
            "password" => isset($data['password']) ? $data['password'] : null,
            "confirmation" => isset($data['confirmation']) ? $data['confirmation'] : null,
            "error" => "Données manquantes!!!!!!"
        ]);
        exit;
    }

    $email = $data['email'];
    $password = $data['password'];
    $confirmation = $data['confirmation'];

    // Vérification de la correspondance des mots de passe
    if ($password !== $confirmation) {
        echo json_encode(["error" => "Les mots de passe ne correspondent pas."]);
        exit;
    }

    // Hashage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Vérification si l'email existe déjà dans la base de données
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $userCount = $stmt->fetchColumn();

    if ($userCount > 0) {
        echo json_encode(["error" => "L'email est déjà utilisé."]);
        exit;
    } else {
        // Insertion de l'utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->execute(['email' => $email, 'password' => $hashedPassword]);

        echo json_encode(["message" => "Inscription réussie."]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>
