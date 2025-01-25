<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once "./vendor/autoload.php";

use Twilio\Rest\Client;

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$sid = getenv("AC9a36ae33a871194ed014e48f05bfe3cd");
$token = getenv("62bf08f12dc31f26defd5310ced2732d");
$twilio = new Client($sid, $token);

$message = $twilio->messages->create(
    "+94777829779", // To
    [
        "body" =>
            "Testing from gasbygas",
        "from" => "+18454156423",
    ]
);

print $message->body;