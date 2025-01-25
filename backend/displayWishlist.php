<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Ensure this is the correct frontend URL in production
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

    // Retrieve idUser from the POST request
    $data = json_decode(file_get_contents('php://input'), true);
    $idUser = isset($data['idUser']) ? $data['idUser'] : null;

    if (!$idUser) {
        echo json_encode(['error' => 'User ID not provided']);
        exit();
    }

    // Prepare the SQL statement
    $sql = "SELECT wishlist.idPlat, plat.nom, plat.price, plat.path_image
            FROM wishliste
            JOIN plat ON wishliste.idPlat = plat.id
            WHERE wishliste.idUser = :idUser";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['idUser' => $idUser]);

    $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($wishlistItems);

} catch (PDOException $e) {
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
}
?>
