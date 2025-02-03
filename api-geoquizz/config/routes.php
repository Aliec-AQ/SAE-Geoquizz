<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;



return function( App $app): App {

    //recoit id série
    $app->post('/games/', PostGameAction::class);


    return $app;
};
