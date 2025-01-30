<?php
require_once 'firebase.php';
include '../../includes/sms_credentials.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

// Fetch Firebase data
$dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();
$crequests = $database->getReference('crequests')->getValue();
$consumers = $database->getReference('consumers')->getValue();
// $selectedOutlets = $database->getReference('outlets')->getValue(); 

$outletId = null;
if (!empty($selectedOutlets)) {
    foreach ($selectedOutlets as $id => $outlet) {
        $outletId = $id;  // Get first outlet ID
        break;
    }
}

if (!$dispatchSchedules || !$crequests || !$consumers || !$outletId) {
    echo json_encode([
        'success' => false,
        'message' => 'No valid data found',
        'debug' => [
            'outletId' => $outletId,
            'dispatchSchedules' => $dispatchSchedules,
            'crequests' => $crequests,
            'consumers' => $consumers,
            'selectedOutlets' => $selectedOutlets
        ]
    ]);
    exit;
}

// Find delivery date
$edelivery = null;
foreach ($dispatchSchedules as $scheduleId => $schedule) {
    if (isset($schedule['outlet_id']) && $schedule['outlet_id'] === $outletId) {
        $edelivery = $schedule['edelivery'];
        break;
    }
}

if (!$edelivery) {
    echo json_encode(['success' => false, 'message' => 'No matching delivery schedule']);
    exit;
}

// Get consumer contacts
$consumerContacts = [];
foreach ($crequests as $request) {
    if (
        isset($request['outlet_id'], $request['edelivery'], $request['consumer_id']) &&
        $request['outlet_id'] === $outletId &&
        $request['edelivery'] === $edelivery
    ) {
        $consumerId = $request['consumer_id'];
        if (isset($consumers[$consumerId]['contact'])) {
            $consumerContacts[$consumerId] = $consumers[$consumerId]['contact'];
        }
    }
}

if (empty($consumerContacts)) {
    echo json_encode(['success' => false, 'message' => 'No consumers found']);
    exit;
}

// Send Notifications
$messageTemplate = "Dear [NAME], \n\n Your delivery is scheduled for [DATE]. Please return empty cylinder with payment on [RDATE].\n\nThank you.";
$successCount = 0;
$errorCount = 0;
$errors = [];

foreach ($consumerContacts as $consumerId => $contact) {
    try {
        $consumer = $consumers[$consumerId];
        $personalizedMessage = str_replace(
            ['[NAME]', '[RDATE]', '[DATE]'],
            [$consumer['name'], date('Y-m-d', strtotime($edelivery) - 2 * 24 * 60 * 60), date('Y-m-d', strtotime($edelivery))],
            $messageTemplate
        );

        $response = sendNotification($contact, $personalizedMessage);

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

echo json_encode([
    'success' => true,
    'message' => "$successCount reminders sent successfully!",
    'errors' => $errors
]);

// Function to send SMS
function sendNotification($phone, $message)
{
    global $apiToken, $senderId, $smsGatewayUrl;

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
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
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