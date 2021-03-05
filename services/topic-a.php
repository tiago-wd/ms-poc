<?php
require './vendor/autoload.php';

use Helloprint\ServiceA;

$serviceA = new ServiceA;

while(true) {
    $message = $serviceA->consumeTopicBroker();
    if($message) {
        $serviceA->sendMessageToBroker($message);
    }

}