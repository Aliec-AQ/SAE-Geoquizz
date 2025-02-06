<?php

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

return  [

    'displayErrorDetails' => true,

    'user.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file('iniconf/auth.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']};";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    'client_geoquizz' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://api.geoquizz:80']);
    },

    'SECRET_KEY' => getenv('JWT_SECRET_KEY'),

    ];