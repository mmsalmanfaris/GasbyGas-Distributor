<?php

include_once 'firebase.php';

if (isset($_GET['schedule_id'])) {
  $scheduleId = $_GET['schedule_id'];
  $database->getReference('dispatch_schedules/' . $scheduleId)->remove();
  header("Location: ../dispatch/?status=datadelete");
}
