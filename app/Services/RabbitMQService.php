<?php

namespace App\Services;

use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected AMQPStreamConnection $connection;

    /**
     * @throws Exception
     */
    public function __construct(?AMQPStreamConnection $connection = null)
    {
        if ($connection === null) {
            $this->connection = new AMQPStreamConnection(
                'rabbitmq',
                5672,
                env('RABBITMQ_USER'),
                env('RABBITMQ_PASSWORD')
            );
        } else {
            $this->connection = $connection;
        }
    }

    public function queueDeclare(string $queueName): void
    {
        $this->channel()->queue_declare(
            $queueName,
            false,
            false,
            false,
            false
        );
    }

    public function channel(): AMQPChannel|AbstractChannel
    {
        return $this->connection->channel();
    }

    public function sendMessage(string $queueName, string $message): void
    {
        $this->queueDeclare($queueName);
        $this->channel()->basic_publish(new AMQPMessage($message), '', $queueName);
    }

    public function receiveMessage(
        string $queueName,
        callable $callback
    ): void
    {
        $this->channel()->queue_declare(
            $queueName,
            false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $this->channel()->basic_consume(
            $queueName,
            '',
            false,
            true,
            false,
            false,
            function ($message) use ($callback) {
                $callback($message);
            }
        );

        while ($this->channel()->is_consuming()) {
            $this->channel()->wait();
        }
    }

    /**
     * @throws Exception
     */
    public function close(): void
    {
        $this->channel()->close();
        $this->connection->close();
    }
}
