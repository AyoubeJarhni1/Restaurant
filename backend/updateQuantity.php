<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$dsn = 'mysql:host=localhost;dbname=restaurant;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $idCart = $data['idCart'];
    $quantity = $data['quantity'];

    if (empty($idCart) || empty($quantity)) {
        echo json_encode(["success" => false, "message" => "idCart ou quantité manquant."]);
        exit;
    }

    // Vérifiez si l'article existe dans le panier
    $stmt = $pdo->prepare("SELECT idCart FROM cart WHERE idCart = :idCart");
    $stmt->execute(['idCart' => $idCart]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        echo json_encode(["success" => false, "message" => "Article non trouvé dans le panier."]);
        exit;
    }

    // Mettez à jour la quantité de l'article
    $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE idCart = :idCart");
    $stmt->execute(['quantity' => $quantity, 'idCart' => $idCart]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Quantité mise à jour avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Aucune mise à jour effectuée."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour: " . $e->getMessage()]);
}
?>
