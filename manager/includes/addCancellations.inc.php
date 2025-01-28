<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  if (isset($data['cancellations']) && is_array($data['cancellations']) && isset($data['selectedPanel'])) {
    $selectedPanel = $data['selectedPanel'];
    $cancellations = $data['cancellations'];


    if ($selectedPanel === 'A' || $selectedPanel === 'B') {
      $crequests = $database->getReference('crequests')->getValue();
      if ($crequests) {
        $panelACrequests = [];
        $panelBCrequests = [];
        foreach ($crequests as $key => $crequest) {
          if ($crequest['panel'] === 'A') {
            $panelACrequests[$key] = $crequest;
          } else if ($crequest['panel'] === 'B') {
            $panelBCrequests[$key] = $crequest;
          }
        }
        $hasMatchingQuantities = true;
        if ($selectedPanel === 'A') {
          foreach ($cancellations as $cancellation) {
            $foundMatch = false;
            foreach ($panelBCrequests as $bKey => $bRequest) {
              if ($bRequest['quantity'] == $cancellation['quantity']) {
                $foundMatch = true;
                break;
              }
            }
            if (!$foundMatch) {
              error_log("No matching quantity found in Panel B for Panel A cancellation with consumer_id: " .  $cancellation['consumer_id'] . " quantity: " . $cancellation['quantity']);
              echo json_encode(['status' => 'error', 'message' => 'No matching quantities in Panel B for Panel A, cancellation is not stored']);
              exit();
            }
          }
        } else if ($selectedPanel === 'B') {
          foreach ($cancellations as $cancellation) {
            $foundMatch = false;
            foreach ($panelACrequests as $aKey => $aRequest) {
              if ($aRequest['quantity'] == $cancellation['quantity']) {
                $foundMatch = true;
                break;
              }
            }
            if (!$foundMatch) {
              error_log("No matching quantity found in Panel A for Panel B cancellation with consumer_id: " . $cancellation['consumer_id'] . " quantity: " . $cancellation['quantity']);
              echo json_encode(['status' => 'error', 'message' => 'No matching quantities in Panel A for Panel B, cancellation is not stored']);
              exit();
            }
          }
        }
      }
    }
    try {
      foreach ($cancellations as $cancellation) {
        $newCancellationRef = $database->getReference('cancellations')->push();
        $newCancellationRef->set($cancellation);
      }
      if ($selectedPanel === 'A' || $selectedPanel === 'B') {
        if ($selectedPanel === 'A') {
          foreach ($panelBCrequests as $bKey => $bRequest) {
            foreach ($panelACrequests as $aKey => $aRequest) {
              if ($aRequest['quantity'] == $bRequest['quantity']) {
                try {
                  $database->getReference("crequests/{$aKey}")->update(['consumer_id' => $bRequest['consumer_id']]);
                  $database->getReference("crequests/{$bKey}")->remove();
                } catch (Exception $e) {
                  echo json_encode(['status' => 'error', 'message' => 'Error updating or deleting Panel B crequests: ' . $e->getMessage()]);
                  exit();
                }
                break;
              }
            }
          }
        } else if ($selectedPanel === 'B') {
          foreach ($panelACrequests as $aKey => $aRequest) {
            foreach ($panelBCrequests as $bKey => $bRequest) {
              if ($aRequest['quantity'] == $bRequest['quantity']) {
                try {
                  $database->getReference("crequests/{$bKey}")->update(['consumer_id' => $aRequest['consumer_id']]);
                  $database->getReference("crequests/{$aKey}")->remove();
                } catch (Exception $e) {
                  echo json_encode(['status' => 'error', 'message' => 'Error updating or deleting Panel A crequests: ' . $e->getMessage()]);
                  exit();
                }
                break;
              }
            }
          }
        }
      }
      echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error saving data to db: ' . $e->getMessage()]);
    }
    exit();
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data format']);
    exit();
  }
}
