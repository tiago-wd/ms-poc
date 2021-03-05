<?php
require './vendor/autoload.php';

use Helloprint\ServiceB;

$serviceB = new ServiceB;

while(true) {
    $message = $serviceB->consumeTopicA();
    $serviceB->saveMessage($message);
}
