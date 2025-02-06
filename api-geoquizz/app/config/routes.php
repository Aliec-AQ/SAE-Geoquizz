<?php
declare(strict_types=1);

use geoquizz\application\actions\GetSequenceByIdAction;
use geoquizz\application\actions\GetHistoriqueGameAction;
use geoquizz\application\actions\GetPublicSequencesAction;
use geoquizz\application\actions\PostGameAction;
use geoquizz\application\actions\PostSequenceReplayAction;
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

    $app->get('/sequences/{id}[/]', GetSequenceByIdAction::class)
        ->setName('getSequenceById');

    $app->post('/sequences/replay[/]', PostSequenceReplayAction::class) // id de la séquence dans la query
        ->setName('postReplaySequence');

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
