<?php


use geoquizz\application\actions\PostGameAction;
use geoquizz\application\providers\auth\JWTManager;
use geoquizz\application\providers\auth\TokenPartieProvider;
use geoquizz\application\providers\auth\TokenPartieProviderInterface;
use geoquizz\core\repositoryInterfaces\AuthRepositoryInterface;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;
use geoquizz\core\services\AuthorisationService;
use geoquizz\core\services\AuthorisationServiceInterface;
use geoquizz\core\services\GameService;
use geoquizz\core\services\GameServiceInterface;
use geoquizz\core\services\TokenPartieService;
use geoquizz\core\services\TokenPartieServiceInterface;
use geoquizz\infrastructure\AdapterAuthRepository;
use geoquizz\infrastructure\AdapterMapsRepository;
use geoquizz\infrastructure\PDOGameRepository;

return [

    //repositories
    GameRepositoryInterface::class => function($container) {
        return new PDOGameRepository($container->get('db'));
    },

    MapsRepositoryInterface::class => function($container) {
        return new AdapterMapsRepository($container->get('client_maps'));
    },

    AuthRepositoryInterface::class => function($container) {
        return new AdapterAuthRepository($container->get('client_auth'));
    },

    //services
    GameServiceInterface::class => function($container) {
        return new GameService($container->get(GameRepositoryInterface::class), $container->get(MapsRepositoryInterface::class));
    },

    AuthorisationServiceInterface::class => function($container) {
        return new AuthorisationService($container->get(AuthRepositoryInterface::class));
    },

    TokenPartieServiceInterface::class => function($container) {
        return new TokenPartieService($container->get(GameRepositoryInterface::class));
    },

    //providers

    TokenPartieProviderInterface::class => function($container) {
        return new TokenPartieProvider($container->get(TokenPartieServiceInterface::class), $container->get(JWTManager::class));
    },

    //actions
    PostGameAction::class => function ($container) {
    return new PostGameAction($container->get(GameServiceInterface::class), $container->get(TokenPartieProviderInterface::class));
},




];