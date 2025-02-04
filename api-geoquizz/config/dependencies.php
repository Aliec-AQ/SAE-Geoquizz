<?php


use application\actions\PostGameAction;
use core\repositoryInterfaces\GameRepositoryInterface;
use core\repositoryInterfaces\MapsRepositoryInterface;
use core\services\GameService;
use core\services\GameServiceInterface;
use infrastructure\AdapterMapsRepository;
use infrastructure\PDOGameRepository;

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