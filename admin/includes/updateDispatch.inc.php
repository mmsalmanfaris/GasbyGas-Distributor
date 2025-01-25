<?php
require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $scheduleId = $_POST['schedule_id'];
  $outletId = $_POST['outlet_id'];
  $requestDate = $_POST['request_date'];
  $scheduledDelivery = $_POST['sdelivery'];
  $expectedDelivery = $_POST['edelivery'];
  $quantity = $_POST['quantity'];
  $status = $_POST['status'];


  $data = [
    'outlet_id' => $outletId,
    'request_date' => $requestDate,
    'sdelivery' => $scheduledDelivery,
    'edelivery' => $expectedDelivery,
    'quantity' => $quantity,
    'status' => $status,
  ];

  try {
    $database->getReference("dispatch_schedules/{$scheduleId}")->update($data);
    header("Location: ../dispatch/?status=dataupdate");
    exit;
  } catch (Exception $e) {
    echo "Error updating schedule: " . $e->getMessage();
  }
}
