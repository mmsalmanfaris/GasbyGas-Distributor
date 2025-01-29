<?php
require_once 'firebase.php';
include '../../includes/sms_credentials.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $consumerIds = $data['consumer_ids'] ?? [];
    $message = $data['message'] ?? '';

    if (empty($consumerIds)) {
        echo json_encode(['success' => false, 'message' => 'No consumers found']);
        exit;
    }

    $successCount = 0;
    $errorCount = 0;
    $errors = [];

    foreach ($consumerIds as $consumerId) {
        try {
            $consumer = $database->getReference("consumers/$consumerId")->getValue();

            if (!$consumer || empty($consumer['contact'])) {
                $errorCount++;
                continue;
            }

            $personalizedMessage = str_replace(
                ['[NAME]', '[DATE]'],
                [$consumer['name'], date('Y-m-d', strtotime('+3 days'))],
                $message
            );

            $response = sendNotification($consumer['contact'], $personalizedMessage);

            if ($response['status'] === 'success') {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = "Consumer $consumerId: " . ($response['message'] ?? 'Unknown error');
            }
        } catch (Exception $e) {
            $errorCount++;
            $errors[] = "Consumer $consumerId: " . $e->getMessage();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Reminders sent successfully!']);
}

function sendNotification($phone, $message)
{
    global $apiToken, $senderId, $smsGatewayUrl;

    // Validate phone number format
    if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
        return ['status' => 'error', 'message' => 'Invalid phone number'];
    }

    $postData = [
        'recipient' => $phone,
        'sender_id' => $senderId,
        'type' => 'plain',
        'message' => $message,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $smsGatewayUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  // Use JSON format
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiToken,
        'Content-Type: application/json',
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['status' => 'error', 'message' => 'Curl error: ' . curl_error($ch)];
    }
    curl_close($ch);

    return json_decode($response, true);
}
?>