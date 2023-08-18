<?php

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/AbstractBroker.php';

class Receiver extends AbstractBroker
{
    public function __construct()
    {
        $this->startConnection();
        $this->receive();
    }

    private function receive(): void
    {
        $channel = $this->connection->channel();

        $channel->queue_declare(self::QUEUE_NAME, false, false, false, false);

        $channel->basic_consume(
            self::QUEUE_NAME,
            '',
            false,
            true,
            false,
            false,
            Closure::fromCallable([$this, 'callback'])
        );

        while ($channel->is_open()) {
            $channel->wait();
        }
    }

    private static function callback(AMQPMessage $message): void
    {
        var_dump($message->getMessageCount());

        echo ' [x] Received ', $message->body, "\n";
    }
}