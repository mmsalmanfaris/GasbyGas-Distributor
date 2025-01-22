<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('/Auth/')
    ->withDatabaseUri('https://gasbygas-97e19-default-rtdb.firebaseio.com/');

$database = $factory->createDatabase();
?>