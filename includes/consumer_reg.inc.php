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
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $type = isset($_POST['type']) ? trim($_POST['type']) : null;
    $nic = isset($_POST['nic']) ? trim($_POST['nic']) : null;
    $rnumber = isset($_POST['rnumber']) ? trim($_POST['rnumber']) : null;
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : null;
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;
    $district = isset($_POST['district']) ? trim($_POST['district']) : null;

    // Validate inputs
    if (empty($name) || empty($email) || empty($type) || empty($contact) || empty($address) || empty($district) || ($type === 'individual' && empty($nic)) || ($type === 'business' && empty($rnumber))) {
        // Redirect with error message if validation fails
        header("Location: ../consumers/index.php?error=invalidinput");
        exit();
    }

    try {
        // Generate a unique ID for the consumer
        $consumer_id = uniqid('consumer_');

        // Prepare data to save
        $consumerData = [
            'consumer_id' => $consumer_id,
            'name' => $name,
            'email' => $email,
            'type' => $type,
            'nic' => $nic,
            'rnumber' => $rnumber,
            'contact' => $contact,
            'address' => $address,
            'district' => $district,
        ];

        // Save data to Firebase Realtime Database
        $database->getReference('consumers/' . $consumer_id)->set($consumerData);

        // Redirect to consumers page with success message
        header("Location: ../consumers/index.php?success=consumeradded");
        exit();
    } catch (Exception $e) {
        // Log the error and redirect with failure message
        error_log('Error registering consumer: ' . $e->getMessage());
        header("Location: ../consumers/index.php?error=registrationfailed");
        exit();
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: ../consumers/index.php");
    exit();
}
