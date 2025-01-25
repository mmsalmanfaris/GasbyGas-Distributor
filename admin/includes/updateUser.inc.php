<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'firebase.php';

    // Ensure the required fields are available
    $type = isset($_POST['type']) ? $_POST['type'] : 'outlet'; // Default to 'outlet' if not provided
    $id = isset($_POST['id']) ? $_POST['id'] : (isset($_POST['outlet_id']) ? $_POST['outlet_id'] : null);

    if (!$id) {
        die("Error: Missing required fields (id or outlet_id).");
    }

    try {
        if ($type === 'user') {
            // Update user data
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
        } elseif ($type === 'outlet') {
            // Update outlet data
            $data = [
                'district' => $_POST['district'],
                'town' => $_POST['town'],
                'name' => $_POST['name'],
                'stock' => $_POST['stock'],
            ];

            $database->getReference("outlets/{$id}")->update($data);
            header("Location: ../outlet/?status=dataupdate");
        } else {
            throw new Exception("Invalid type specified.");
        }
        exit;
    } catch (Exception $e) {
        echo "Error updating data: " . $e->getMessage();
    }
}
