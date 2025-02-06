<?php
declare(strict_types=1);

use geoquizz\application\actions\GetHighscoreAction;
use geoquizz\application\actions\GetGameByIdAction;
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

    $app->get('/game[/]', GetGameByIdAction::class)
        ->add(AuthorisationPartieMiddleware::class)
        ->setName('getGame');

    $app->post('/game[/]', PostGameAction::class)     //recoit id série dans la query
        ->add(AuthorisationMiddleware::class)
        ->setName('createGame');

    $app->put('/game[/]',PutFinishGameAction::class)
        ->add(AuthorisationPartieMiddleware::class)
        ->setName('finishGame');

    $app->get('/sequences/public[/]', GetPublicSequencesAction::class)
        ->setName('getPublicSequences');

    $app->post('/sequences/replay[/]', PostSequenceReplayAction::class) // id de la séquence dans la query
    ->setName('postReplaySequence');

    $app->put('/sequences/{idSequence}/status[/]',PutSequenceStatusAction::class)
        ->setName('putStatusSequences');


    $app->get('/users/games[/]', GetHistoriqueGameAction::class)
        ->add(AuthorisationMiddleware::class)
        ->setName('getHistoriqueGames');

    $app->post('/players[/]',PostPlayerAction::class)
        ->setName('createPlayer');

    $app->get('/series/{id}/highscore[/]', GetHighscoreAction::class)
        ->add(AuthorisationMiddleware::class)
        ->setName('getHighscore');

    return $app;
};
