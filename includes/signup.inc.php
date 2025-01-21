<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    echo "Request method is POST";


    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {

        require_once 'dbh.inc.php';  
        require_once 'signup_model.inc.php';
        require_once 'signup_contr.inc.php';

        // Error handling
        $errors = [];

        if (is_input_empty($username, $email, $password)) {
            $errors["empty_input"] = "Fill in all fields!";
        }

        if (is_input_invalid($email)) {
            $errors["invalid_email"] = "Enter a valid email!";
        }

        if (is_username_taken($pdo, $username)) {
            $errors["username_taken"] = "Username already taken!";
        }

        if (is_email_registered($pdo, $email)) {  // Corrected variable here
            $errors["email_registered"] = "Email already registered!";
        }

        require_once 'config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;

            $signupData = [
                'username' => $username,
                'email' => $email
            ];
            $_SESSION['signup_data'] = $signupData;
            
            header("Location: ../dashboard/signup.php");
            die();
        } 

        create_user($pdo, $username, $email, $password);

        header("Location: ../dashboard/signup.php?signup=success");
        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

} else {
    echo "Request method is not POST. Redirecting...";
    header("Location: ../index.php");
    die();
}
