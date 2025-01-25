<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include Firebase configuration
    require 'firebase.php'; // Adjust the path if needed

    // Ensure $database is available
    if (!isset($database)) {
        die("Firebase Database configuration not initialized.");
    }

    // Your update logic
    $userId = $_POST['user_id'];
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'contact' => $_POST['contact'],
        'nic' => $_POST['nic'],
        'is_admin' => isset($_POST['isAdmin']) ? true : false,
        'outlet_id' => $_POST['outlet_id'], // New
    ];

    if (!empty($_POST['password'])) {
        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    try {
        $database->getReference("users/{$userId}")->update($data);
        header("Location: ../user/?status=dataupdate");
        exit;
    } catch (Exception $e) {
        echo "Error updating user: " . $e->getMessage();
    }
}
