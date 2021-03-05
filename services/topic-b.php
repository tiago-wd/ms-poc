<?php
require './vendor/autoload.php';

use Helloprint\ServiceB;

$serviceB = new ServiceB;

while(true) {
    $message = $serviceB->consumeTopicA();
    if($message)
        $serviceB->saveMessage($message);
}
