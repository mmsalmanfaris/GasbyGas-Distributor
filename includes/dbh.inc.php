<?php

$host = 'localhost';
$dbname = 'digitalpartner';
$dbusername = 'root';
$dbpassword = '';


try {
    $pdo = new pdo("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed:" .$e->getMessage());
}