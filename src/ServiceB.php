<?php

namespace Helloprint;

use PDO;
use RdKafka\Conf;
use RdKafka\Consumer;
use RdKafka\TopicConf;

class ServiceB
{
    private $con;

    public function __construct()
    {
        $this->con = new PDO('pgsql:host=postgres;dbname=helloprint', 'hpuser', 'secret');        
    }

    public function consumeTopicA()
    {
        $conf = new Conf();

        $conf->set('group.id', 'myConsumerGroup');
        $kafka = new Consumer($conf);
        $kafka->addBrokers('kafka');

        $topicConf = new TopicConf();
        $topicConf->set('auto.commit.interval.ms', 100);

        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('auto.offset.reset', 'smallest');

        $topic = $kafka->newTopic('topic-b', $topicConf);

        $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);
        $message = $topic->consume(0, 120*10000);
        
        if($message->err == RD_KAFKA_RESP_ERR_NO_ERROR) {
            $payload = json_decode($message->payload);
            return json_encode([
                'token' => $payload->token,
                'message' => "{$payload->message} Bye"
            ]);
        }
    }

    public function saveMessage($message) 
    {           
        $query = $this->con->prepare("UPDATE request SET finished = true, message = :message where token = :token");
        $params = json_decode($message);
        
        $query->execute([
            ':token' => $params->token,
            ':message' => $params->message,
        ]);
    }
}