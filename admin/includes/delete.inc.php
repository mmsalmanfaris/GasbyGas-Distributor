<?php

include_once 'firebase.php';

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type']; // 'user' or 'outlet'
    $id = $_GET['id'];

    try {
        if ($type === 'user') {
            $database->getReference('users/' . $id)->remove();
            header("Location: ../user/?status=datadelete");
        } elseif ($type === 'outlet') {
            $database->getReference('outlets/' . $id)->remove();
            header("Location: ../outlet/?status=datadelete");
        } else {
            throw new Exception("Invalid type specified.");
        }
        exit;
    } catch (Exception $e) {
        echo "Error deleting data: " . $e->getMessage();
    }
} else {
    echo "Error: Missing required parameters.";
}
