<?php

use GuzzleHttp\Client;
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

    'SECRET_KEY' => getenv('JWT_SECRET_KEY'),

];