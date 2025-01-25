<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust to match your frontend URL
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve idClient from the POST request
    $data = json_decode(file_get_contents('php://input'), true);
    $idClient = $data['idClient'];

    if (!$idClient) {
        echo json_encode(['error' => 'Client ID not provided']);
        exit();
    }

    // Prepare the SQL statement
    $sql = "SELECT cart.idCart, plat.nom, plat.price, plat.path_image
            FROM cart
            JOIN plat ON cart.idplat = plat.id
            WHERE cart.idUser = :idClient";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['idClient' => $idClient]);

    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cartItems);

} catch (PDOException $e) {
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
}
?>
