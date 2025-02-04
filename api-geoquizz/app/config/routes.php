<?php
declare(strict_types=1);

use app\src\application\actions\PostGameAction;
use Slim\App;


return function( App $app): App {

    $app->post('/games[/]', PostGameAction::class)     //recoit id série dans la query
        ->setName('createGame');

    $app->put('/games/{id}[/]', PutGameAction::class)
        ->setName('finishGame');

    $app->get('/sequence/{id}[/]', GetGameAction::class)
        ->setName('getSequence');

    $app->post('/games/replay', PostGameReplayAction::class) // id de la séquence dans la query
        ->setName('postReplayGame');

    $app->get('/games/public[/]', GetPublicGamesAction::class)
        ->setName('getPublicGames');

    $app->get('/users/{id}/games[/]', GetUserGamesAction::class)
        ->setName('getUserGames');



    return $app;
};
