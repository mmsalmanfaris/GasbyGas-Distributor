<?php

include_once 'firebase.php';

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id']; // User ID

    try {
        // Remove user data from Firebase
        $database->getReference('users/' . $id)->remove();
        header("Location: ../user/?status=datadelete");
        exit;
    } catch (Exception $e) {
        echo "Error deleting user: " . $e->getMessage();
    }
} else {
    echo "Error: Missing required parameter (id).";
}
