<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Récupérez les données de la requête POST
$data = json_decode(file_get_contents('php://input'), true);
$idPlat = $data['idPlat'];
$idUser = $data['idUser']; // Utiliser l'ID utilisateur envoyé dans la requête

// Configuration de la connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=restaurant;charset=utf8';
$username = 'root';
$password = '';

try {
    // Créez une connexion PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparez et exécutez la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO wishliste (idPlat, idUser) VALUES (:idPlat, :idUser)");
    $stmt->execute(['idPlat' => $idPlat, 'idUser' => $idUser]);

    echo json_encode(["status" => "success", "message" => "Plat ajouté à la liste de souhaits pour l'utilisateur ID: $idUser"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout à la liste de souhaits: " . $e->getMessage()]);
}
?>
