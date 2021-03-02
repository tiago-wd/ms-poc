<?php
require './vendor/autoload.php';

use Helloprint\ServiceB;

$serviceB = new ServiceB;
// add async here
$message = $serviceB->consumeTopicA();
$serviceB->saveMessage($message);
