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

return function(App $app): App {

    $app->add(Cors::class);

    $app->options('/{routes:.+}', function(Request $rq, Response $rs, array $args): Response {
        return $rs;
    });

    /*************************
    * Routes de l'API Map
    *************************/

    $app->get('/assets/{id}[/]', GeneriqueMapAction::class)
        ->setName('getAsset');

    $app->get('/items/themes[/]', GeneriqueMapAction::class)
        ->setName('getSeries');

    /*************************
    * Routes de l'API GeoQuizz
    *************************/

    $app->get('/sequences/public[/]', GeneriqueGeoQuizzAction::class)
        ->setName('getPublicGames');

    $app->post('/game[/]', GeneriqueGeoQuizzAction::class)
        ->add(AuthMiddleware::class)
        ->setName('createGame');

    $app->put('/game[/]', GeneriqueGeoQuizzAction::class)
        ->setName('finishGame');

    $app->get('/game[/]', GeneriqueGeoQuizzAction::class)
        ->setName('getGameById');

    $app->post('/sequences/replay[/]', GeneriqueGeoQuizzAction::class)
        ->setName('postReplaySequence');

    $app->put('/sequences/{idSequence}/status[/]', GeneriqueGeoQuizzAction::class)
        ->add(AuthMiddleware::class)
        ->setName('putStatusSequences');

    $app->get('/users/games[/]', GeneriqueGeoQuizzAction::class)
        ->add(AuthMiddleware::class)
        ->setName('getHistoriqueGames');

    $app->post('/players[/]', GeneriqueGeoQuizzAction::class)
        ->setName('createUser');
    
    $app->get('/series/{id}/highscore[/]', GeneriqueGeoQuizzAction::class)
        ->add(AuthMiddleware::class)
        ->setName('getHighscore');

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
