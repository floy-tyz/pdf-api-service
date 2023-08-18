<?php

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/AbstractBroker.php';

class Sender extends AbstractBroker
{
    public function __construct(string $message)
    {
        $this->startConnection();
        $this->sendMessage($message);
        $this->stopConnection();
    }

    private function sendMessage(string $message): void
    {
        $channel = $this->connection->channel();

        $channel->queue_declare(self::QUEUE_NAME, false, false, false, false);
        $channel->basic_publish($this->createMessage($message), '', self::QUEUE_NAME);

        $channel->close();
    }

    private function createMessage(string $message): AMQPMessage
    {
        return new AMQPMessage($message);
    }
}