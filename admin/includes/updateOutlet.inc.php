<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'firebase.php';

    // Ensure the required fields are available
    $id = isset($_POST['id']) ? $_POST['id'] : (isset($_POST['outlet_id']) ? $_POST['outlet_id'] : null);

    if (!$id) {
        die("Error: Missing required field (id or outlet_id).");
    }

    try {
        // Update outlet data
        $data = [
            'district' => $_POST['district'],
            'town' => $_POST['town'],
            'name' => $_POST['name'],
            'stock' => $_POST['stock'],
        ];

        $database->getReference("outlets/{$id}")->update($data);
        header("Location: ../outlet/?status=dataupdate");
        exit;
    } catch (Exception $e) {
        echo "Error updating outlet: " . $e->getMessage();
    }
}
