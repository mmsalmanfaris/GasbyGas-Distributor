<?php

include_once 'firebase.php';

if (isset($_GET['outlet_id'])) {
  $outlet_id = $_GET['outlet_id'];
  $database->getReference('outlets/' . $outlet_id)->remove();
  header("Location: ../outlet/?status=datadelete");
  exit();
}
