<?php
require './vendor/autoload.php';

use Helloprint\ServiceA;

$serviceA = new ServiceA;

// add async here
$message = $serviceA->consumeTopicBroker();
$serviceA->sendMessageToBroker($message);
