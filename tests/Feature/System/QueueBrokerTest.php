<?php

namespace System;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\TestCase;

class QueueBrokerTest extends TestCase
{
    private static AMQPStreamConnection $connection;

    private string $host;
    private int $port;
    private string $login;
    private string $password;

    public function setUp(): void
    {
        parent::setUp();

        $def = 'rabbitmq';
        $this->host = config("queue.connections.$def.host");
        $this->port = config("queue.connections.$def.port");
        $this->login = config("queue.connections.$def.login");
        $this->password = config("queue.connections.$def.password");
    }

    /**
     * HTTP only
     */
    public function testGetQueueList(): void
    {
        $r = Http::withBasicAuth($this->login, $this->password)
            ->get("$this->host:1$this->port/api/queues");

        self::assertEquals(200, $r->status(), 'Wrong connect to RabbitMQ with queue ');

        $body = $r->body();
        self::assertNotEmpty($body);

        $body = json_decode($body, true);
        self::assertNotEmpty($body);
    }

    private function connectAMQP(): void
    {
        try {
            self::$connection = new AMQPStreamConnection($this->host, $this->port, $this->login, $this->password);
            self::assertTrue(self::$connection->isConnected(), 'RabbitMQ not connected');
        } catch (\Exception $exception) {
            self::fail($exception->getMessage());
        }
    }

    public function testGetQueues(): void
    {
        $response = Http::withBasicAuth($this->login, $this->password)->get("$this->host:1$this->port/api/queues");

        $queues = array_column(json_decode($response, true), 'name');

        self::assertNotEmpty($queues);
    }

    public function testSetMessage(): void
    {
        $this->connectAMQP();
        $queue = 'update_catalog';
        $exchange = 'router';

        $channel = self::$connection->channel();
        /**
         * name: $queue
         * passive: false
         * durable: true // the queue will survive server restarts
         * exclusive: false // the queue can be accessed in other channels
         * auto_delete: false //the queue won't be deleted once the channel is closed.
         */
        $channel->queue_declare($queue, false, true, false, false);

        /**
         * name: $exchange
         * type: direct
         * passive: false
         * durable: true // the exchange will survive server restarts
         * auto_delete: false //the exchange won't be deleted once the channel is closed.
         */
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);

        $expectedMessage = Str::random();

        $message = new AMQPMessage($expectedMessage, [
            'content_type'  => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($message, $exchange);

        // Insert
        $message = $channel->basic_get($queue)?->getBody();
        self::assertEquals($expectedMessage, $message);

        $messageReread = $channel->basic_get($queue);
        self::assertNull($messageReread);

        // Delete queue
        $isDeleted = $channel->queue_delete($queue);
        self::assertEquals(0, $isDeleted);

        $channel->close();
        self::$connection->close();

        self::assertFalse(self::$connection->isConnected(), 'RabbitMQ not connected');
    }

    public function testCreateQueues(): void
    {
        $list = [
            'update_catalog',
            'queue2',
            'queue3',
        ];

        foreach ($list as $queue) {
            $this->connectAMQP();

            $exchange = 'router';

            $channel = self::$connection->channel();
            /**
             * name: $queue
             * passive: false
             * durable: true // the queue will survive server restarts
             * exclusive: false // the queue can be accessed in other channels
             * auto_delete: false //the queue won't be deleted once the channel is closed.
             */
            $channel->queue_declare($queue, false, true, false, false);

            /**
             * name: $exchange
             * type: direct
             * passive: false
             * durable: true // the exchange will survive server restarts
             * auto_delete: false //the exchange won't be deleted once the channel is closed.
             */
            $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

            $channel->queue_bind($queue, $exchange);

            $channel->close();
            self::$connection->close();

            self::assertFalse(self::$connection->isConnected(), 'RabbitMQ not connected');
        }
    }
}