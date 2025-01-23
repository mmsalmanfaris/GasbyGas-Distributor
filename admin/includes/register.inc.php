<?php
require '../../vendor/autoload.php';

use Kreait\Firebase\Factory;


// Initialize Firebase
include_once 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $nic = $_POST['nic'];
    $isAdmin = isset($_POST['isAdmin']) ? true : false;

    try {
        // Register user in Firebase Authentication
        $createdUser = $auth->createUserWithEmailAndPassword($email, $password);
        $userId = $createdUser->uid; // Get the UID of the created user

        // Add user details to Firebase Realtime Database
        $database->getReference("users/$userId")->set([
            'contact' => $contact,
            'email' => $email,
            'is_admin' => $isAdmin,
            'name' => $name,
            'nic' => $nic,
            'user_id' => $userId
        ]);

        header("Location: ../user/?status=datasuccess");

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>