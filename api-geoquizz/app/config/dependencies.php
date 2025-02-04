<?php


use app\src\application\actions\PostGameAction;
use app\src\core\repositoryInterfaces\GameRepositoryInterface;
use app\src\core\repositoryInterfaces\MapsRepositoryInterface;
use app\src\core\services\GameService;
use app\src\core\services\GameServiceInterface;
use app\src\infrastructure\AdapterMapsRepository;
use app\src\infrastructure\PDOGameRepository;

return [

    //actions
    PostGameAction::class => function ($container) {
        return new PostGameAction($container->get(GameServiceInterface::class));
    },

    //services
    GameServiceInterface::class => function($container) {
        return new GameService($container->get(GameRepositoryInterface::class), $container->get(MapsRepositoryInterface::class));
    },

    //repositories
    GameRepositoryInterface::class => function($container) {
        return new PDOGameRepository($container->get('db'));
    },

    MapsRepositoryInterface::class => function($container) {
        return new AdapterMapsRepository($container->get('db'));
    }




];