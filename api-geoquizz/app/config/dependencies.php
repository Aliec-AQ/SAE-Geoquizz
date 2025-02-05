<?php


use geoquizz\application\actions\GetPublicSequencesAction;
use geoquizz\application\actions\PostGameAction;
use geoquizz\application\middlewares\AuthorisationMiddleware;
use geoquizz\application\middlewares\AuthorisationPartieMiddleware;
use geoquizz\application\providers\tokenPartie\TokenPartieProvider;
use geoquizz\application\providers\tokenPartie\TokenPartieProviderInterface;
use geoquizz\application\providers\tokenPartie\JWTManager;
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
use Psr\Container\ContainerInterface;

return [

    //JWT
    JWTManager::class => function(ContainerInterface $c){
        return new JWTManager($c->get('SECRET_KEY'));
    },

    //repositories
    GameRepositoryInterface::class => function(ContainerInterface $container) {
        return new PDOGameRepository($container->get('geoquizz.pdo'));
    },

    MapsRepositoryInterface::class => function(ContainerInterface $container) {
        return new AdapterMapsRepository($container->get('client_maps'));
    },

    AuthRepositoryInterface::class => function(ContainerInterface $container) {
        return new AdapterAuthRepository($container->get('client_auth'));
    },

    //services
    GameServiceInterface::class => function(ContainerInterface $container) {
        return new GameService($container->get(GameRepositoryInterface::class), $container->get(MapsRepositoryInterface::class));
    },

    AuthorisationServiceInterface::class => function(ContainerInterface $container) {
        return new AuthorisationService($container->get(AuthRepositoryInterface::class));
    },

    TokenPartieServiceInterface::class => function(ContainerInterface $container) {
        return new TokenPartieService($container->get(GameRepositoryInterface::class));
    },

    //providers

    TokenPartieProviderInterface::class => function(ContainerInterface $container) {
        return new TokenPartieProvider($container->get(TokenPartieServiceInterface::class), $container->get(JWTManager::class));
    },

    //actions
    PostGameAction::class => function (ContainerInterface $container) {
    return new PostGameAction($container->get(GameServiceInterface::class), $container->get(TokenPartieProviderInterface::class));
    },

    GetPublicSequencesAction::class => function (ContainerInterface $container) {
        return new GetPublicSequencesAction($container->get(GameServiceInterface::class));
    },

    //middleware

    AuthorisationMiddleware::class => function(ContainerInterface $container){
        return new AuthorisationMiddleware($container->get(AuthorisationServiceInterface::class));
    },

    AuthorisationPartieMiddleware::class => function(ContainerInterface $container){
    return new AuthorisationPartieMiddleware($container->get(JWTManager::class),$container->get(TokenPartieServiceInterface::class),$container->get(TokenPartieProviderInterface::class));
    }




];