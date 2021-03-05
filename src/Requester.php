<?php

namespace Helloprint;

use Helloprint\Broker;

class Requester 
{
    public function sendMessageToBroker($message)
    {
        $broker = new Broker;
        return $broker->getRequestedMessage($message);
    }

    public function getBrokerMessages($callback) 
    {
        $wait = 0.0005;
        $stopWatch = 0;
        while ($stopWatch <= 1) {
            $callback();
            sleep($wait);
            $stopWatch += $wait;
        }
    }
}