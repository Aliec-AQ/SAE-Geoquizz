<?php

namespace geoquizz\core\repositoryInterfaces;

interface RabbitMQRepositoryInterface
{
    public function publish($message, $routingKey):void;
}