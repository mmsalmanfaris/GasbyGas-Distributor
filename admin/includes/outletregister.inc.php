<?php
require '../../vendor/autoload.php';

use Kreait\Firebase\Factory;


include_once 'firebase.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $district = trim($_POST['district']);
    $name = trim($_POST['name']);
    $town = trim($_POST['town']);
    $stock = intval($_POST['stock']);


    if (empty($district) || empty($name) || empty($town)) {
        header("Location: ../outlet/?status=dataerror");
        exit();
    }


    try {
        // Generate a unique ID for the outlet
        $outlet_id = uniqid('outlet_');

        $outletData = [
            'district' => $district,
            'name' => $name,
            'town' => $town,
            'stock' => $stock,
        ];

        // Save data to Firebase Realtime Database
        $database->getReference('outlets/' . $outlet_id)->set($outletData);

        header("Location: ../outlet/?status=datasuccess");
        exit();

    } catch (Exception $e) {
        error_log('Error registering outlet: ' . $e->getMessage());
        header("Location: ../outlet/?status=dataerror");
        exit();
    }
} else {
    header("Location: ../outlet/?status=dataerror");
    exit();
}
