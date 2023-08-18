<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class AbstractBroker
{
    const QUEUE_NAME = 'hello_queue';

    protected ?AMQPStreamConnection $connection = null;

    protected function startConnection(): void
    {
        try {
            $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }

    protected function stopConnection(): void
    {
        try {
            $this->connection->close();
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}