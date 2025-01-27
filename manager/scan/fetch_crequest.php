<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require '../includes/firebase.php';

// Get POST data
$request = json_decode(file_get_contents('php://input'), true);

if (!isset($request['consumer_id'])) {
    echo json_encode(['success' => false, 'message' => 'consumer_id is required']);
    exit;
}

$consumer_id = $request['consumer_id'];

try {
    // Fetch all crequest nodes
    $crequests = $database->getReference('crequests')->getValue();

    $matchedCrequest = null;

    if ($crequests) {
        // Loop through each crequest to find the matching consumer_id
        foreach ($crequests as $crequest_id => $crequest) {
            if (isset($crequest['consumer_id']) && $crequest['consumer_id'] === $consumer_id) {
                $matchedCrequest = [
                    'id' => $crequest_id,
                    'data' => $crequest
                ];
                break;
            }
        }
    }

    if ($matchedCrequest) {
        echo json_encode([
            'success' => true,
            'crequest' => $matchedCrequest
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the given consumer_id.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>