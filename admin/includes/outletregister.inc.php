<?php
// Include Firebase configuration
require '../../vendor/autoload.php';

// Initialize Firebase with correct service account and database URI
use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('../../gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json') // Ensure the path to your service account file is correct
    ->withDatabaseUri('https://gasbygas-97e19-default-rtdb.firebaseio.com'); // Use the actual URL for your Firebase database

$database = $factory->createDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $district = isset($_POST['district']) ? trim($_POST['district']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $town = isset($_POST['town']) ? trim($_POST['town']) : null;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;

    // Validate inputs
    if (empty($district) || empty($name) || empty($town) || $stock < 0) {
        // Redirect with error message if validation fails
        header("Location: ../outlet/index.php?error=invalidinput");
        exit();
    }

    try {
        // Generate a unique ID for the outlet
        $outlet_id = uniqid('outlet_');

        // Prepare data to save
        $outletData = [
            'outlet_id' => $outlet_id,
            'district' => $district,
            'name' => $name,
            'town' => $town,
            'stock' => $stock,
        ];

        // Save data to Firebase Realtime Database
        $database->getReference('outlets/' . $outlet_id)->set($outletData);

        // Redirect to outlet page with success message
        header("Location: ../outlet/index.php?success=outletadded");
        exit();
    } catch (Exception $e) {
        // Log the error and redirect with failure message
        error_log('Error registering outlet: ' . $e->getMessage());
        header("Location: ../outlet/index.php?error=registrationfailed");
        exit();
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: ../outlet/index.php");
    exit();
}
