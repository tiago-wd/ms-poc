<?php

const KAFKA_PARTITION = 0;
const KAFKA_TOPIC_TEST = 'test';

require_once './vendor/autoload.php';


$conf = new RdKafka\Conf();

$conf->set('group.id', 'myConsumerGroup');

$kafka = new RdKafka\Consumer($conf);
$kafka->addBrokers('kafka');

$topicConf = new RdKafka\TopicConf();
$topicConf->set('auto.commit.interval.ms', 100);

$topicConf->set('offset.store.method', 'broker');
$topicConf->set('auto.offset.reset', 'smallest');

$topic = $kafka->newTopic(KAFKA_TOPIC_TEST, $topicConf);

$topic->consumeStart(KAFKA_PARTITION, RD_KAFKA_OFFSET_STORED);

while (true) {
    $message = $topic->consume(KAFKA_PARTITION, 120*10000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            print($message->payload . "\n");
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            // $logger->debug('No more messages; will wait for more');
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            // $logger->warn('Timed out');
            break;
        default:
            // $logger->err($message->errstr() . ' - ' . $message->err);
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
}