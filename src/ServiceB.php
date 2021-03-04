<?php

namespace Helloprint;

use Helloprint\Broker;
use RdKafka\Conf;
use RdKafka\Consumer;
use RdKafka\TopicConf;

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

        $conf = new Conf();

        $conf->set('group.id', 'myConsumerGroup');
        $kafka = new Consumer($conf);
        $kafka->addBrokers('kafka');

        $topicConf = new TopicConf();
        $topicConf->set('auto.commit.interval.ms', 100);

        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('auto.offset.reset', 'smallest');

        $topic = $kafka->newTopic('topic-a', $topicConf);

        $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);
        $message = $topic->consume(0, 120*10000);
        
        $name = $this->names[array_rand($this->names)];
        //add bye to payload
        if($message->err == RD_KAFKA_RESP_ERR_NO_ERROR) {
            return $message->payload;
        }
    }

    public function saveMessage($message) 
    {           
        return;
    }
}