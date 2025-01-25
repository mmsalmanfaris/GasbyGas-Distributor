if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$contact = $_POST['contact'];
$nic = $_POST['nic'];
$isAdmin = isset($_POST['isAdmin']) ? true : false;
$outlet_id = $_POST['outlet_id']; // New

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
'user_id' => $userId,
'outlet_id' => $outlet_id //New
]);

header("Location: ../user/?status=datasuccess");
} catch (Exception $e) {
echo "Error: " . $e->getMessage();
}
}


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
        // Generate a unique ID for the outlet
        $user_id = uniqid('user_');

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
        $database->getReference('outlets/' . $user_id)->set($userData);

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
