<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require '../../gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json'; // Include Firebase SDK

use Kreait\Firebase\Factory;

// Initialize Firebase
$firebase = (new Factory())->withServiceAccount('../includes/firebase.php');
$database = $firebase->createDatabase();

// Decode JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['consumer_id'])) {
    $consumer_id = $input['consumer_id'];

    // Fetch data from Firebase
    $crequest = $database->getReference("crequests/{$consumer_id}")->getValue();

    if ($crequest) {
        echo json_encode(['success' => true, 'crequest' => $crequest]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found for the given ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
