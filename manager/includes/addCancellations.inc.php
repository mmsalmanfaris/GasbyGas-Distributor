<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  if (isset($data['cancellations']) && is_array($data['cancellations'])) {
    try {
      foreach ($data['cancellations'] as $cancellation) {
        $newCancellationRef = $database->getReference('cancellations')->push();
        $newCancellationRef->set($cancellation);
      }
      echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error saving to db: ' . $e->getMessage()]);
    }
    exit();
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data format']);
    exit();
  }
}
