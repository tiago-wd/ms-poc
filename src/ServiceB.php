<?php

namespace Helloprint;

use Helloprint\Broker;
use Kafka\ConsumerConfig;
use Kafka\Consumer;
use Kafka\ProducerConfig;
use Kafka\Producer;

class ServiceB
{
    public function consumeTopicA()
    {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList('127.0.0.1:9092');
        $config->setGroupId('topic-b');
        $config->setBrokerVersion('1.0.0');
        $config->setTopics(['topic-b']);
        $consumer = new Consumer();

        $consumer->start(function($topic, $part, $message) {            
            $name = $this->names[array_rand($this->names)];
            $newMessage = [
                "msg" => "Hi {$name} \n Bye",
                "token" => $message
            ];

            $this->saveMessage($message);
        });
    }

    public function saveMessage($message) 
    {           
        return;
    }
}