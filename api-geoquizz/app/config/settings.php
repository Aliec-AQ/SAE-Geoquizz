<?php

use GuzzleHttp\Client;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

return  [

    'displayErrorDetails' => true,

    'geoquizz.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file('iniconf/geoquizz.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']};";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    'client_maps' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://directus:8055']);
    },

    'client_auth' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://api.auth.geoquizz:80']);
    },

    'channel' => function (ContainerInterface $c) {
        $rabbit = parse_ini_file('iniconf/geoquizz.rabbitmq.ini');
        $connection = new AMQPStreamConnection($rabbit['host'], $rabbit['port'], $rabbit['user'], $rabbit['password']);
        $channel = $connection->channel();
        return $channel;
    },

    'SECRET_KEY' => getenv('JWT_SECRET_KEY'),

];