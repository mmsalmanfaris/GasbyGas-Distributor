<?php
require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  $outletId = $data['outlet_id'];
  $requestDate = $data['request_date'];
  $scheduledDelivery = null;
  $expectedDelivery = $data['edelivery'];
  $quantity = $data['quantity'];
  $status = $data['status'];
  $createdAt = $data['created_at'];

  $newScheduleRef = $database->getReference('dispatch_schedules')->push();
  $newScheduleKey = $newScheduleRef->getKey();

  $data = [
    'outlet_id' => $outletId,
    'request_date' => $requestDate,
    'sdelivery' => $scheduledDelivery,
    'edelivery' => $expectedDelivery,
    'quantity' => $quantity,
    'status' => $status,
    'created_at' => $createdAt,
  ];


  $newScheduleRef->set($data);
  header('Content-Type: application/json');
  echo json_encode(['status' => 'success']);
  exit();
} else {
  header('Content-Type: application/json');
  echo json_encode(['status' => 'failed', 'message' => 'Invalid Request Method']);
}
