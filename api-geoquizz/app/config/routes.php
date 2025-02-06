<?php
declare(strict_types=1);

use geoquizz\application\actions\GetGameByIdAction;
use geoquizz\application\actions\GetHistoriqueGameAction;
use geoquizz\application\actions\GetPublicSequencesAction;
use geoquizz\application\actions\PostGameAction;
use geoquizz\application\actions\PostPlayerAction;
use geoquizz\application\actions\PutFinishGameAction;
use geoquizz\application\actions\PutSequenceStatusAction;
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

    $app->put('/games[/]',PutFinishGameAction::class)
        ->add(AuthorisationPartieMiddleware::class)
        ->setName('finishGame');

    $app->get('/games/{id}[/]', GetGameByIdAction::class)
        ->add(AuthorisationPartieMiddleware::class)
        ->setName('getGame');

    $app->get('/sequence/{id}[/]', GetGameAction::class)
        ->setName('getSequence');

    $app->post('/games/replay', PostGameReplayAction::class) // id de la séquence dans la query
        ->setName('postReplayGame');

    $app->get('/sequences/public[/]', GetPublicSequencesAction::class)
        ->setName('getPublicSequences');

    $app->put('/sequences/{idSequence}/status[/]',PutSequenceStatusAction::class)
        ->setName('putStatusSequences');

    $app->get('/users/games[/]', GetHistoriqueGameAction::class)
        ->add(AuthorisationMiddleware::class)
        ->setName('getHistoriqueGames');

    $app->post('/players[/]',PostPlayerAction::class)
        ->setName('createPlayer');

    return $app;
};
