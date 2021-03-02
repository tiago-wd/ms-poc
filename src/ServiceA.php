<?php

namespace Helloprint;

use Helloprint\Broker;
use Kafka\ConsumerConfig;
use Kafka\Consumer;
use Kafka\ProducerConfig;
use Kafka\Producer;

class ServiceA
{
    private $names = [
        'Joao', 
        'Bram', 
        'Gabriel', 
        'Fehim', 
        'Eni', 
        'Patrick', 
        'Micha', 
        'Mirzet', 
        'Liliana',
        'Sebastien'
    ];

    public function consumeTopicBroker()
    {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList('127.0.0.1:9092');
        $config->setGroupId('broker');
        $config->setBrokerVersion('1.0.0');
        $config->setTopics(['broker']);
        $consumer = new Consumer();

        $consumer->start(function($topic, $part, $message) {            
            $name = $this->names[array_rand($this->names)];
            return [
                "msg" => "Hi {$name}",
                "token" => $message
            ];
        });

    }

    public function sendMessageToBroker($messageToBroker) 
    {           
        $broker = new Broker;
        $broker->getTopicAMessage($messageToBroker);
    }
}