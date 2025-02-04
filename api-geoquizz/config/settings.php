<?php

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

    'SECRET_KEY' => getenv('JWT_SECRET_KEY'),

];