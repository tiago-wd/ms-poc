<?php
require './vendor/autoload.php';

use Helloprint\ServiceA;

$serviceA = new ServiceA;

while(true) {
    $message = $serviceA->consumeTopicBroker();
    $serviceA->sendMessageToBroker($message);
}
