<?php

const KAFKA_PARTITION = 0;
const KAFKA_TOPIC_TEST = 'test';

require_once './vendor/autoload.php';

$conf = new RdKafka\Conf();

$kafka = new RdKafka\Producer($conf);
$kafka->addBrokers('kafka');

$topic = $kafka->newTopic(KAFKA_TOPIC_TEST);

$message = 'Message sent';
print(sprintf("Producing: %s \n", $message));
$topic->produce(KAFKA_PARTITION, 0, $message);
$kafka->poll(0);
$result = $kafka->flush(10000);
