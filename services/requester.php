<?php
require './vendor/autoload.php';

use Helloprint\Requester;
use Helloprint\Broker;

$request = new Requester;
$broker = new Broker;

$token = $request->sendMessageToBroker('Hi');

// $request->getBrokerMessages(function() use ($broker, $token) {
//     $broker->messages($token);
// });
