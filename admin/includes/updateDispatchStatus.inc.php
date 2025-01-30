<?php
require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $outlet = $_POST['outlet'] ?? null;
  $delivery_date = $_POST['delivery_date'] ?? null;


  // Check if the required data exists
  if (!$outlet || !$delivery_date) {
    echo json_encode(['success' => false, 'message' => 'Missing required data.']);
    exit();
  }

  // Fetch all dispatch schedules
  $dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();

  if ($dispatchSchedules) {
    $latestScheduleId = null;

    // Find the latest schedule for the selected outlet
    foreach ($dispatchSchedules as $scheduleId => $schedule) {
      if (isset($schedule['outlet_id']) && $schedule['outlet_id'] === $outlet) {
        $latestScheduleId = $scheduleId;
        break; // Exit once we find the first match
      }
    }


    if ($latestScheduleId) {
      // Prepare data to update
      $updates = [
        'sdelivery' => $delivery_date,
        'status' => 'scheduled'
      ];

      // Update the selected dispatch schedule
      $database->getReference("dispatch_schedules/{$latestScheduleId}")->update($updates);

      // Redirect after updating
      header("Location: ../dispatch/?status=dataupdate");

      include_once '../includes/sendReminder.inc.php';

      exit();
    } else {
      // If no matching schedule is found
      echo json_encode(['success' => false, 'message' => 'No matching delivery schedule found']);
      exit();
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'No dispatch schedules found']);
    exit();
  }
}
?>