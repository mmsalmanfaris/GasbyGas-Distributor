<?php
require '../includes/firebase.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$consumer_id = $data['consumer_id'] ?? '';

if ($consumer_id) {
    try {
        // Retrieve the crequests node
        $crequests = $database->getReference('crequests')->getValue();

        foreach ($crequests as $key => $crequest) {
            if ($crequest['consumer_id'] === $consumer_id) {
                // Update empty_cylinder and payment_status
                $database->getReference("crequests/{$key}")
                    ->update([
                        'empty_cylinder' => 'received',
                        'payment_status' => 'received',
                    ]);

                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Consumer ID not found']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No Consumer ID provided']);
}
?>