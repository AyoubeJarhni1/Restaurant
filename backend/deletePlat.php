<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Récupérez les données de la requête POST
$data = json_decode(file_get_contents('php://input'), true);
$nomPlat = $data['nom']; // Le nom du plat envoyé dans la requête
$idUser = $data['idUser'];   // L'ID de l'utilisateur envoyé dans la requête

// Configuration de la connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=restaurant;charset=utf8';
$username = 'root';
$password = '';

try {
    // Créez une connexion PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Sélectionnez l'ID du plat en fonction du nom
    $stmt = $pdo->prepare("SELECT id FROM plat WHERE nom = :nomPlat");
    $stmt->bindParam(':nomPlat', $nomPlat);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $idPlat = $result['id'];

        // 2. Supprimez l'entrée dans `cart` en utilisant l'ID du plat et l'ID de l'utilisateur
        $deleteStmt = $pdo->prepare("DELETE FROM cart WHERE idplat = :idPlat AND idUser = :idUser");
        $deleteStmt->bindParam(':idPlat', $idPlat);
        $deleteStmt->bindParam(':idUser', $idUser);
        $deleteStmt->execute();

        echo json_encode(["status" => "success", "message" => "Plat supprimé du panier", "idPlat" => $idPlat]);
    } else {
        echo json_encode(["status" => "error", "message" => "Plat non trouvé", "nomPlat" => $nomPlat]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de l'opération: " . $e->getMessage()]);
}
?>
