<?php
require_once 'firebase.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $consumerId = $input['consumer_id'] ?? null;
    $message = $input['message'] ?? '';

    try {
        if (!$consumerId) {
            throw new Exception('Consumer ID is required');
        }

        // Get consumer details
        $consumerRef = $database->getReference("consumers/$consumerId");
        $consumer = $consumerRef->getValue();

        if (!$consumer) {
            throw new Exception('Consumer not found');
        }

        // Personalize message
        $personalizedMessage = str_replace(
            '[NAME]',
            $consumer['name'] ?? 'Customer',
            $message
        );

        // Send notifications
        $smsSent = sendSMS($consumer['contact'], $personalizedMessage);
        $emailSent = sendEmail($consumer['email'], 'GasByGas Reminder', $personalizedMessage);

        if ($smsSent || $emailSent) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Failed to send both SMS and email');
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function sendSMS($phone, $message) {
    // Implement actual SMS gateway integration here
    error_log("SMS to $phone: $message");
    return true; // Simulate success
}

function sendEmail($email, $subject, $body) {
    // Implement actual email sending logic here
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    error_log("Email to $email: $subject - $body");
    return true; // Simulate success
}