<?php
require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selectedDistrict = $_POST['district'];
  $deliveryDate = $_POST['delivery_date'];

  // Fetch all dispatch schedules
  $dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();

  // Fetch all outlets
  $outlets = $database->getReference('outlets')->getValue();


  if ($dispatchSchedules && $outlets) {
    foreach ($dispatchSchedules as $scheduleId => $schedule) {
      foreach ($outlets as $outletId => $outlet) {
        if ($outlet['district'] == $selectedDistrict && $outlet['outlet_id'] == $schedule['outlet_id']) {
          $updates = [
            'sdelivery' => $deliveryDate,
            'status' => 'dispatched'
          ];
          $database->getReference("dispatch_schedules/{$scheduleId}")->update($updates);
        }
      }
    }
  }
  header("Location: ../dispatch/?status=dataupdate");
  exit();
}
