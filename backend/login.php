<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$dsn = 'mysql:host=localhost;dbname=restaurant;charset=utf8';
$username = 'root';
$password = '' ;

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    // Assurez-vous que les mots de passe sont stockés de manière sécurisée
    $stmt = $pdo->prepare("SELECT idClient, nomClient FROM user WHERE email = :email AND password = :password");
    $stmt->execute(['email' => $email, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["success" => true, "idUser" => $user['idClient'], "name" => $user['nomClient']]);
    } else {
        echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la connexion: " . $e->getMessage()]);
}
?>
