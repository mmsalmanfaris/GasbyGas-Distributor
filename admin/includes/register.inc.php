<?php
require '../vendor/autoload.php';

use Kreait\Firebase\Factory;


// Initialize Firebase
$factory = (new Factory)->withServiceAccount('../gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json');
$auth = $factory->createAuth();
$database = $factory->createDatabase();


// Initialize Firebase
$factory = (new Factory)
    ->withServiceAccount('../gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json')
    ->withDatabaseUri('https://gasbygas-97e19-default-rtdb.firebaseio.com/');

$auth = $factory->createAuth();
$database = $factory->createDatabase();

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