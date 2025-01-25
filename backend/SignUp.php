<?php
// Configurer les en-têtes pour les requêtes CORS et le format JSON
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'restaurant';
$username = 'root';  // Assurez-vous que ce nom d'utilisateur est correct
$password = '';       // Assurez-vous que ce mot de passe est correct

try {
    // Créer une connexion PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);

    $data = json_decode(file_get_contents("php://input"));

    // Logs pour vérifier les données reçues
    error_log(print_r($data, true));

    // Vérifier que les champs requis sont définis
    if (isset($data->name) && isset($data->email) && isset($data->password)) {
        $name = $data->name;
        $email = $data->email;
        $password = $data->password;

        // Hacher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Préparer et exécuter la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO user (nomClient, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashed_password]);

        echo json_encode(["message" => "Utilisateur enregistré avec succès."]);
    } else {
        echo json_encode(["message" => "Entrées invalides."]);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Erreur de connexion : " . $e->getMessage()]);
}
?>
