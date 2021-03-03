<?php

namespace Helloprint;

use PDO;
use Ramsey\Uuid\Uuid;
use Kafka\ProducerConfig;
use Kafka\Producer;

class Broker extends PDO
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

    public function createRequest($message) 
    {   
        $uuid = Uuid::uuid4();
        $query = $this->con->prepare("INSERT INTO request1 (token, message) VALUES (:uuid, :message)");
        
        $params = [
            ':uuid' => $uuid->toString(),
            ':message' => $message
        ];
        $query->execute($params);
        $this->sendMessageToA($params);
        return $uuid;

    }

    public function sendMessageToA($params)
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList('kafka:9092');
        $config->setBrokerVersion('1.0.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);

        $producer = new Producer(
            function() use ($params) {
                return [
                    [
                        'topic' => 'topic-a',
                        'value' => json_encode($params),
                        'key' => 'key',
                    ],
                ];
            }
        );

        $producer->success(function($result) {
            return $result;
        });
        $producer->error(function($errorCode) {
            return $errorCode;
        });
        $producer->send(true);

    }

    public function getTopicAMessage($message)
    {
        return $this->sendMessageToB($message);
    }

    public function sendMessageToB($message)
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList('localhost:9092');
        $config->setBrokerVersion('1.0.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);

        $producer = new Producer(
            function() use ($message) {
                return [
                    [
                        'topic' => 'topic-b',
                        'value' => json_encode($message),
                        'key' => 'key',
                    ],
                ];
            }
        );

        $producer->success(function($result) {
            return $result;
        });
        $producer->error(function($errorCode) {
            return $errorCode;
        });
        $producer->send(true);

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