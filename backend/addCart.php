<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Récupérez les données de la requête POST
$data = json_decode(file_get_contents('php://input'), true);
$idplat = $data['idplat'];
$quantity = $data['quantity'];
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
    $stmt = $pdo->prepare("INSERT INTO cart (idplat, quantity, idUser) VALUES (:idplat, :quantity, :idClient)");
    $stmt->execute(['idplat' => $idplat, 'quantity' => $quantity, 'idClient' => $idUser]);

    echo json_encode(["status" => "success", "message" => "Produit ajouté au panier pour l'utilisateur ID: $idUser"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout au panier: " . $e->getMessage()]);
}
?>
