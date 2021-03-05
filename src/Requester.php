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
            $message = $callback();
            sleep($wait);
            $stopWatch += $wait;
            if($message != '') {
                echo $message;
                continue;
            }
        }
        echo "no response";
    }
}