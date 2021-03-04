<?php

namespace Helloprint;

use Helloprint\Broker;
use RdKafka\Conf;
use RdKafka\Consumer;
use RdKafka\TopicConf;

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
        //add name to payload
        if($message->err == RD_KAFKA_RESP_ERR_NO_ERROR) {
            return $message->payload;
        }
    }

    public function sendMessageToBroker($messageToBroker) 
    {           
        $broker = new Broker;
        $broker->getTopicAMessage($messageToBroker);
    }
}