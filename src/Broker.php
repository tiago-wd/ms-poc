<?php

namespace Helloprint;

use PDO;
use Ramsey\Uuid\Uuid;
use RdKafka\Conf;
use RdKafka\Producer;

class Broker
{
    private $con;

    public function __construct()
    {
        $this->con = new PDO('pgsql:host=postgres;dbname=helloprint', 'hpuser', 'secret');        
    }

    public function getRequestedMessage($message)
    {
        return $this->createRequest($message);
    }

    public function createRequest($request) 
    {   
        $uuid = Uuid::uuid4();
        $query = $this->con->prepare("INSERT INTO request (token, message) VALUES (:uuid, :message)");
        
        $message = [
            'token' => $uuid->toString(),
            'message' => $request
        ];
        $query->execute([
            ':uuid' => $message['token'],
            ':message' => $message['message']
        ]);
        $this->sendMessageToA($message);
        return $message['token'];

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

        $topic->produce(0, 0, $params);
        $kafka->poll(0);
        $kafka->flush(10000);

    }

    public function messages($token)
    {
        $query = $this->con->query("SELECT * FROM request WHERE token = '$token' and finished = true");
        while ($row = $query->fetch()) {
            $getMessage = "{$row['message']} \n";
        }
        return $getMessage ?? null;
    }

}