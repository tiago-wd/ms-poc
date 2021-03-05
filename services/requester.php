<?php
require './vendor/autoload.php';

use Helloprint\Requester;
use Helloprint\Broker;

$request = new Requester;
$broker = new Broker;

$token = $request->sendMessageToBroker('Hi');

$message = null;
$request->getBrokerMessages(function() use ($broker, $token, &$message) {
    $message = $broker->messages($token);
});

echo $message ?? "no response";