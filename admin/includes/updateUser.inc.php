<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'firebase.php';

    // Ensure the required fields are available
    $id = $_POST['id'] ?? null;

    if (!$id) {
        die("Error: Missing required field (id).");
    }

    try {
        // Your update logic
        $userId = $_POST['user_id'];
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'contact' => $_POST['contact'],
            'nic' => $_POST['nic'],
            'is_admin' => isset($_POST['isAdmin']) ? true : false,
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $database->getReference("users/{$id}")->update($data);
        header("Location: ../user/?status=dataupdate");
        exit;
    } catch (Exception $e) {
        echo "Error updating user: " . $e->getMessage();
    }
}
