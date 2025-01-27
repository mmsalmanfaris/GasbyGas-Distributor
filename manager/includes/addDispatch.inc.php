<?php
include_once 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $outletId = $_POST['outlet_id'];
  $requestDate = $_POST['request_date'];
  $scheduledDelivery = $_POST['sdelivery'];
  $expectedDelivery = $_POST['edelivery'];
  $quantity = $_POST['quantity'];
  $status = $_POST['status'];


  $newScheduleRef = $database->getReference('dispatch_schedules')->push();
  $newScheduleKey = $newScheduleRef->getKey();


  $data = [
    'outlet_id' => $outletId,
    'request_date' => $requestDate,
    'sdelivery' => $scheduledDelivery,
    'edelivery' => $expectedDelivery,
    'quantity' => $quantity,
    'status' => $status,
    'created_at' => date('c'),
  ];

  $newScheduleRef->set($data);

  header("Location: ../dispatch/?status=datasuccess");
  exit();
}
