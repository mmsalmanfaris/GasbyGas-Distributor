<?php
require 'firebase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Authenticate user
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $userId = $signInResult->firebaseUserId();

        echo "User ID: $userId<br>";

        // Test retrieving all users
        $users = $database->getReference("users")->getValue();
        echo "All Users: <br>";
        print_r($users);

        // Retrieve specific user
        $user = $database->getReference("users/$userId")->getValue();

        if ($user) {
            session_start();
            $_SESSION['user_id'] = $userId;
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['name'] = $user['name'];

            // Redirect based on user role
            if ($user['is_admin']) {
                header('Location: ../admin/'); // Admin dashboard
            } else {
                header('Location: ../manager/'); // Manager dashboard
            }
            exit();
        } else {
            echo "User not found in the database.";
        }
    } catch (Exception $e) {
        echo "Login failed: " . $e->getMessage();
        header('Location: ../manager/');
    }
}
?>