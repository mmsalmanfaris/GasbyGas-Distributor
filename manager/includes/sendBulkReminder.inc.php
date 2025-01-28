<?php
require_once 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $consumerIds = $data['consumer_ids'] ?? [];
    $message = $data['message'] ?? '';

    try {
        foreach ($consumerIds as $consumerId) {
            $consumer = $database->getReference("consumers/$consumerId")->getValue();
            
            // Customize message with consumer data
            $personalizedMessage = str_replace(
                ['[NAME]', '[DATE]'],
                [$consumer['name'], date('Y-m-d', strtotime('+3 days'))],
                $message
            );
            
            // Implement your notification logic here
            sendNotification($consumer['contact'], $consumer['email'], $personalizedMessage);
        }
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function sendNotification($phone, $email, $message) {
    // Implement actual SMS/email integration here
    // Example using a mock function:
    error_log("Notification sent to $phone and $email with message: $message");
    return true;
}