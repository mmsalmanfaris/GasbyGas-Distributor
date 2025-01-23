<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;


// Initialize Firebase
$factory = (new Factory)->withServiceAccount('gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json');
$auth = $factory->createAuth();
$database = $factory->createDatabase();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
</head>

<body>
    <form action="" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="contact">Contact:</label><br>
        <input type="text" id="contact" name="contact" required><br><br>

        <label for="nic">NIC:</label><br>
        <input type="text" id="nic" name="nic" required><br><br>

        <label for="isAdmin">Is Admin:</label><br>
        <input type="checkbox" id="isAdmin" name="isAdmin"><br><br>

        <input type="submit" value="Register">
    </form>

    <?php


    // Initialize Firebase
    $factory = (new Factory)
        ->withServiceAccount('./gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json')
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

            echo "User registered successfully!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>

</body>

</html>