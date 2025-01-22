<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('./gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json');
$database = $factory->createDatabase();

try {
    $users = $database->getReference('users')->getValue();
    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>