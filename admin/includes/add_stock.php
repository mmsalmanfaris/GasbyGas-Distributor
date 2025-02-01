<?php
session_start();
include_once 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the quantity from the form
        $quantity = intval($_POST['quantity']);
        
        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }

        // Get current stock
        $stockRef = $database->getReference('stock');
        $currentStock = $stockRef->getValue();
        
        // Calculate new stock
        $newQuantity = isset($currentStock['available']) ? 
            intval($currentStock['available']) + $quantity : 
            $quantity;

        // Update the stock in Firebase
        $updates = [
            'available' => $newQuantity,
            'last_updated' => date('Y-m-d H:i:s'),
            'updated_by' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'unknown'
        ];
        
        $stockRef->update($updates);

        // Set success message
        $_SESSION['success_message'] = "Successfully added $quantity units to stock. New total: $newQuantity units";
    } catch (Exception $e) {
        // Set error message
        $_SESSION['error_message'] = "Error adding stock: " . $e->getMessage();
    }
} else {
    // If not POST request, set error message
    $_SESSION['error_message'] = "Invalid request method";
}

// Redirect back to stock management page
header('Location: ../stock/index.php');
exit();
