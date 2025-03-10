<?php
require '../../vendor/autoload.php';

use Kreait\Firebase\Factory;


include_once 'firebase.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $nic = trim($_POST['nic']);
    $isAdmin = isset($_POST['isAdmin']) ? true : false;
    $outlet_id = $_POST['outlet_id']; // New


    if (empty($email) || empty($password) || empty($name) || empty($contact) || empty($nic)) {
        header("Location: ../user/?status=dataerror");
        exit();
    }


    try {
        // // Generate a unique ID for the outlet
        // $user_id = uniqid('user_');

        $createdUser = $auth->createUserWithEmailAndPassword($email, $password);
        $userId = $createdUser->uid;

        $userData = [
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'contact' => $contact,
            'nic' => $nic,
            'is_admin' => $isAdmin,
            'outlet_id' => $outlet_id
        ];

        // Save data to Firebase Realtime Database
        $database->getReference('users/' . $userId)->set($userData);

        header("Location: ../user/?status=datasuccess");
        exit();

    } catch (Exception $e) {
        error_log('Error registering user: ' . $e->getMessage());
        header("Location: ../user/?status=dataerror");
        exit();
    }
} else {
    header("Location: ../user/?status=dataerror");
    exit();
}
