<?php

declare(strict_types=1);

function check_login_errors()
{
    if (isset($_SESSION["errors_login"])) {
        $errors = $_SESSION["errors_login"];

        foreach ($errors as $error) {
            echo "<p class='alert alert-danger'> $error </p>";
        }
        unset($_SESSION["errors_login"]); // Clear errors after displaying
    }
}
