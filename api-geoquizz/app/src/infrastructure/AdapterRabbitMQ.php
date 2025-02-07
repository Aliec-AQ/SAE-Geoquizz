<?php

namespace geoquizz\infrastructure;

use geoquizz\core\repositoryInterfaces\RabbitMQRepositoryInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class AdapterRabbitMQ implements RabbitMQRepositoryInterface
{
    private AMQPChannel $channel;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function publish($message, $routingKey): void
    {
        try {
            $msg_body = $message;
            $msg = new AMQPMessage(json_encode($msg_body));
            $this->channel->basic_publish($msg, 'game', $routingKey);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}