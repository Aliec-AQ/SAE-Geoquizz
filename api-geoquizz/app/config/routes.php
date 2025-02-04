<?php
declare(strict_types=1);

use app\src\application\actions\PostGameAction;
use Slim\App;


return function( App $app): App {

    $app->post('/games[/]', PostGameAction::class)     //recoit id série dans la query
        ->setName('createGame');

    $app->put('/games/{id}[/]', PutGameAction::class)
        ->setName('finishGame');

    $app->get('/games/{id}[/]', GetGameAction::class)
        ->setName('getGame');

    $app->get('/games/public[/]', GetPublicGamesAction::class)
        ->setName('getPublicGames');

    $app->get('/users/{id}/games[/]', GetUserGamesAction::class)
        ->setName('getUserGames');

    return $app;
};
