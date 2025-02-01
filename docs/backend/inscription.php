<?php
header('Content-Type: application/json');

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
    echo json_encode(["message" => "Connexion réussie à la base de données."]);
    
    // Traitement des données envoyées via POST (inscription)
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmation'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmation = $_POST['confirmation'];

        // Vérification de la correspondance des mots de passe
        if ($password !== $confirmation) {
            echo json_encode(["error" => "Les mots de passe ne correspondent pas."]);
        } else {
            // Hashage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Vérification si l'email existe déjà dans la base de données
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $userCount = $stmt->fetchColumn();

            if ($userCount > 0) {
                echo json_encode(["error" => "L'email est déjà utilisé."]);
            } else {
                // Insertion de l'utilisateur dans la base de données
                $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
                $stmt->execute(['email' => $email, 'password' => $hashedPassword]);

                echo json_encode(["message" => "Inscription réussie."]);
            }
        }
    } else {
        echo json_encode(["error" => "Données manquantes."]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>
