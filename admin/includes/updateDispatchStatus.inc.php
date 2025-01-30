<?php
require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selectedDistrict = $_POST['district'];
  $deliveryDate = $_POST['delivery_date'];

  // Fetch all dispatch schedules
  $dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();

  // Fetch all outlets
  $outlets = $database->getReference('outlets')->getValue();

  $selectedOutlets = []; // To store matching outlets


  if ($dispatchSchedules && $outlets) {
    foreach ($outlets as $outletId => $outlet) {
      if ($outlet['district'] == $selectedDistrict) {
        $selectedOutlets[$outletId] = $outlet; // Store selected outlets
      }
    }

    foreach ($dispatchSchedules as $scheduleId => $schedule) {
      if (isset($selectedOutlets[$schedule['outlet_id']])) {
        $updates = [
          'sdelivery' => $deliveryDate,
          'status' => 'scheduled'
        ];
        $database->getReference("dispatch_schedules/{$scheduleId}")->update($updates);
      }
    }
  }

  // Print or return the selected outlets (for debugging)
  // echo json_encode(['selected_outlets' => array_values($selectedOutlets)]);
  include_once 'sendReminder.inc.php';

  // Redirect (optional)
  // header("Location: ../dispatch/?status=dataupdate");
  // exit();
}
?>"