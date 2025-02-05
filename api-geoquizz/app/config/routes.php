<?php
declare(strict_types=1);

use geoquizz\application\actions\PostGameAction;
use geoquizz\application\middlewares\AuthorisationMiddleware;
use geoquizz\application\middlewares\AuthorisationPartieMiddleware;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


return function( App $app): App {

    $app->get('/ping[/]', function (Request $request, Response $response) {
        $response->getBody()->write('pong');
        return $response;
    });

    $app->post('/games[/]', PostGameAction::class)     //recoit id série dans la query
        ->add(AuthorisationMiddleware::class)
        ->setName('createGame');

    $app->put('/games/{id}[/]', PutGameAction::class)
        ->setName('finishGame');

    $app->get('/sequence/{id}[/]', GetGameAction::class)
        ->setName('getSequence');

    $app->post('/games/replay', PostGameReplayAction::class) // id de la séquence dans la query
        ->setName('postReplayGame');

    $app->get('/sequences/public[/]', GetPublicSequencesAction::class)
        ->setName('getPublicSequences');

    $app->get('/users/{id}/games[/]', GetUserGamesAction::class)
        ->setName('getUserGames');



    return $app;
};
