<?php

declare(strict_types=1);

function signup_inputs(){
                            
    if(isset($_SESSION["signup_data"]["username"]) && !isset($_SESSION["error_signup"]["username_taken"])){
        echo'
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="form-outline">
                        <label class="form-label" for="username">Username </label>
                        <input type="text" name="username" class="form-control form-control-lg" value="'.$_SESSION["signup_data"]["username"] . '" />
                    </div>
                </div>
            </div>
        ';
    }
    else{
        echo '
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="form-outline">
                        <label class="form-label" for="username">Username </label>
                        <input type="text" name="username" class="form-control form-control-lg"/>
                    </div>
                </div>
            </div>
        ';
    }

    if(isset($_SESSION["signup_data"]["email"]) && !isset($_SESSION["error_signup"]["invalid_email"]) && !isset($_SESSION["error_signup"]["email_registered"])){ 
        echo'
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="form-outline">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" name="email" class="form-control form-control-lg" value="'. $_SESSION["signup_data"] ["email"] .'">
                    </div>
                </div>
            </div>
        ';
    }
    else{
        echo'
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="form-outline">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" name="email" class="form-control form-control-lg" value="">
                    </div>
                </div>
            </div>
        ';
    }

    echo'
        <div class="row">
            <div class="col-md-12 mb-4 pb-2">
                <div class="form-outline">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" name="password" class="form-control form-control-lg" />
                </div>
            </div>
        </div>
    ';
}

function check_signup_errors(){
    if(isset($_SESSION["errors_signup"])){
        $errors = $_SESSION["errors_signup"];
        
        echo "<br>";

        foreach($errors as $error){
            echo '<p class="alert alert-warning">' . $error . '</p>';
        }
 
        unset($_SESSION["errors_signup"]);
    }
    elseif(isset($_GET["signup"]) && $_GET["signup"] == "success"){
        echo '<br>';
        echo '<p class="alert alert-success"> Signup Succcess!</p>';
    }
}