<?php

declare(strict_types=1);


function get_username(object $pdo, string $username){
    $query = "SELECT username FROM tbl_admin WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;

}

function get_email(object $pdo, string $email){
    $query = ('SELECT email FROM tbl_admin WHERE email = :email;');
    $stmt = $pdo -> prepare($query);
    $stmt -> bindParam(":email", $email);
    $stmt -> execute();

    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    return $result;

}

function set_user(object $pdo, string $username, string $email, string $password){
    $query = "INSERT INTO tbl_admin (username, email, password) VALUES(:username, :email, :password);";
    $stmt = $pdo -> prepare($query);

    $option = [
        'cost' => 12
    ];
    $hashpwd = password_hash($password, PASSWORD_BCRYPT, $option);
        
    
    $stmt -> bindParam(":username", $username);
    $stmt -> bindParam(":email", $email);
    $stmt -> bindParam(":password", $hashpwd);
    $stmt -> execute();
}