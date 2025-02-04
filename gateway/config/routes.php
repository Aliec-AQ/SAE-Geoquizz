<?php
declare(strict_types=1);

use gateway\application\actions\GeneriqueAuthnAction;
use gateway\application\actions\GeneriqueGeoQuizzAction;
use gateway\application\actions\GeneriqueMapAction;
use gateway\application\middleware\AuthMiddleware;
use gateway\application\middleware\Cors;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;


return function( App $app): App {

    $app->add(Cors::class);

    $app->options('/{routes:.+}',
        function( Request $rq,
                  Response $rs, array $args) : Response {
            return $rs;
        });

    /*************************
    * Routes de l'API Map
    *************************/

    $app->get('/assets/{id}[/]', GeneriqueMapAction::class)
        ->setName('getAsset');

    /*************************
     * Routes de l'API GeoQuizz
     *************************/

     $app->get('/games/public[/]', GeneriqueGeoQuizzAction::class)
     ->setName('getPublicGames');

     $app->post('/games[/]', GeneriqueGeoQuizzAction::class)
     ->setName('createGame');

 $app->put('/games/{id}[/]', GeneriqueGeoQuizzAction::class)
     ->setName('finishGame');

 $app->get('/games/{id}[/]', GeneriqueGeoQuizzAction::class)
     ->setName('getGame');

 $app->get('/users/{id}/games[/]', GeneriqueGeoQuizzAction::class)
     ->setName('getUserGames');

    /*************************
     * Routes de l'API Auth
     *************************/

    $app->post('/signin[/]', GeneriqueAuthnAction::class)
        ->setName('usersSignIn');

    $app->post('/register[/]', GeneriqueAuthnAction::class)
        ->setName('usersRegister');

    $app->post('/refresh[/]', GeneriqueAuthnAction::class)
        ->add(AuthMiddleware::class)
        ->setName('usersRefresh');



    return $app;
};