<?php
require '../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Initialize Firebase with the correct database URL
$factory = (new Factory)
    ->withServiceAccount('../gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json')
    ->withDatabaseUri('https://gasbygas-97e19-default-rtdb.firebaseio.com'); // Add your database URL here

$auth = $factory->createAuth();
$database = $factory->createDatabase();
?>