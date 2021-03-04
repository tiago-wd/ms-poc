<?php

namespace Helloprint;

use PDO;
use Ramsey\Uuid\Uuid;
use RdKafka\Conf;
use RdKafka\Producer;

class Broker extends PDO
{
    private $con;

    public function __construct()
    {
        // $this->con = new PDO('pgsql:host=postgres;dbname=helloprint', 'hpuser', 'secret');        
    }

    public function getRequestedMessage($message)
    {
        return $this->createRequest($message);
    }

    public function createRequest($message) 
    {   
        $uuid = Uuid::uuid4();
        // $query = $this->con->prepare("INSERT INTO request1 (token, message) VALUES (:uuid, :message)");
        
        $params = [
            ':uuid' => $uuid->toString(),
            ':message' => $message
        ];
        // $query->execute($params);
        $this->sendMessageToA($params);
        return $uuid;

    }

    public function sendMessageToA($params)
    {
        $conf = new Conf();

        $kafka = new Producer($conf);
        $kafka->addBrokers('kafka');

        $topic = $kafka->newTopic('topic-a');

        $topic->produce(0, 0, json_encode($params));
        $kafka->poll(0);
        $kafka->flush(10000);

    }

    public function getTopicAMessage($message)
    {
        return $this->sendMessageToB($message);
    }

    public function sendMessageToB($params)
    {
        $conf = new Conf();

        $kafka = new Producer($conf);
        $kafka->addBrokers('kafka');

        $topic = $kafka->newTopic('topic-b');

        $topic->produce(0, 0, json_encode($params));
        $kafka->poll(0);
        $kafka->flush(10000);

    }

    public function messages($token)
    {
        echo $token;
        // if updated send token to requester
    }

}

// $broker = new Broker;
// $message = $broker->createRequest();
// $broker->sendMessageToA($message);