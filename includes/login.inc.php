<?php
require 'firebase.php';
require_once 'config_session.inc.php'; // Start session here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Authenticate user
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $userId = $signInResult->firebaseUserId();

        // Retrieve specific user
        $user = $database->getReference("users/$userId")->getValue();

        if ($user) {
            //session_start();  // NO!  Already started in config_session.inc.php
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
            // User exists in Auth, but not in Realtime DB.  This is an error.
            $_SESSION["errors_login"] = ["User not found in database."];
            header('Location: ../index.php');
            exit();
        }
    } catch (\Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
        $_SESSION["errors_login"] = ["Incorrect password."];  // Specific error
        header('Location: ../index.php');
        exit();
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        $_SESSION["errors_login"] = ["User not found."]; // Specific error
        header('Location: ../index.php');
        exit();
    } catch (\Kreait\Firebase\Exception\AuthException $e) {
        // Other Firebase Auth exceptions (less common)
        $_SESSION["errors_login"] = ["Authentication error: " . $e->getMessage()];
        header('Location: ../index.php');
        exit();
    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
        // Other Firebase exceptions (e.g. database connection issues)
        $_SESSION["errors_login"] = ["Login error: " . $e->getMessage()];
        header('Location: ../index.php');
        exit();
    } catch (Exception $e) {
        // Catch *any* other exception (very important for debugging)
        $_SESSION["errors_login"] = ["An unexpected error occurred: " . $e->getMessage()];
        header('Location: ../index.php');
        exit();
    }

    // We *should not* get here. If we do, there's a logic error.
    // Add an error message and redirect just in case.
    $_SESSION["errors_login"] = ["An unexpected error occurred."];
    header('Location: ../index.php');
    exit();
} else {
    // If it's not a POST request, redirect to login page.
    header('Location: ../index.php');
    exit();
}
