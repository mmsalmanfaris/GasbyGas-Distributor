<?php

include_once 'firebase.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $database->getReference('users/' . $user_id)->remove();
    header("Location: ../user/?status=datadelete");
}
