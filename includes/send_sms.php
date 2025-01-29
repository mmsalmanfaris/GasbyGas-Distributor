<?php
// SMS Gateway credentials
$apiToken = '227|5qfDmqCDJLKbyGGjCyFqvqemumN3B20DAZSjK5Ch672532ef'; // Replace with your API token
$smsGatewayUrl = 'https://app.text.lk/api/v3/sms/send'; // API endpoint
$senderId = 'TextLKDemo'; // Replace with your sender ID

// SMS details
$recipient = '94761754242'; // Replace with the recipient number
$message = 'This is a test message from the Text.lk API!'; // Replace with your message
$type = 'plain'; // Message type (use 'plain' for text messages)

// Payload
$data = [
    'recipient' => $recipient,
    'sender_id' => $senderId,
    'type' => $type,
    'message' => $message,
];

// cURL setup for POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $smsGatewayUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiToken,
    'Content-Type: application/json',
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'Response:' . $response;
}

curl_close($ch);
?>