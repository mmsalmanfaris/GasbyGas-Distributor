<?php
require '../../vendor/autoload.php';
include_once '../includes/firebase.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['is_available'])) {
    try {
        $status = filter_var($_POST['is_available'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($status === null) {
            echo json_encode(["success" => false, "message" => "Invalid status value"]);
            exit;
        }

        // Update Firebase
        $database->getReference('headoffice/head_office_id_1')
            ->update(['is_available' => $status]);

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>