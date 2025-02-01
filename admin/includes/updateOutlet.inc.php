<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'firebase.php';

    $id = $_POST['outlet_id'];

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
