<?php
require '../../vendor/autoload.php';
include_once '../includes/firebase.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['newStock'])) {
    try {
        $newStock = intval($_POST['newStock']);
        if ($newStock <= 0) {
            echo json_encode(["success" => false, "message" => "Stock amount must be greater than zero."]);
            exit;
        }

        // Generate a unique key for the new stock entry
        $newStockRef = $database->getReference('stock')->push();

        // Data to insert
        $stockData = [
            'available' => $newStock,
            'last_updated' => date("Y-m-d")
        ];

        // Insert into Firebase
        $newStockRef->set($stockData);

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>