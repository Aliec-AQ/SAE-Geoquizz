<?php

use geoquizz_auth\application\actions\GetPlayerIDAction;
use Psr\Container\ContainerInterface;
use geoquizz_auth\application\actions\RefreshAction;
use geoquizz_auth\application\actions\RegisterAction;
use geoquizz_auth\application\actions\SignInAction;
use geoquizz_auth\application\actions\ValidateAction;
use geoquizz_auth\application\providers\auth\AuthProvider;
use geoquizz_auth\application\providers\auth\AuthProviderInterface;
use geoquizz_auth\application\providers\auth\JWTManager;
use geoquizz_auth\core\repositoryInterfaces\UserRepositoryInterface;
use geoquizz_auth\core\services\auth\AuthService;
use geoquizz_auth\core\services\auth\AuthServiceInterface;
use geoquizz_auth\core\services\user\UserService;
use geoquizz_auth\core\services\user\UserServiceInterface;
use geoquizz_auth\infrastructure\repositories\PDOUserRepository;


return [

    JWTManager::class => function(ContainerInterface $c){
        return new JWTManager($c->get('SECRET_KEY'));
    },

    AuthProviderInterface::class => function(ContainerInterface $c){
        return new AuthProvider($c->get(AuthServiceInterface::class),$c->get(JWTManager::class));
    },

    UserRepositoryInterface::class => function(ContainerInterface $c){
        return new PDOUserRepository($c->get('user.pdo'));
    },

    UserServiceInterface::class => function(ContainerInterface $c){
        return new UserService($c->get(UserRepositoryInterface::class));
    },

    AuthServiceInterface::class => function(ContainerInterface $c){
        return new AuthService($c->get(UserRepositoryInterface::class));
    },

    SignInAction::class => function(ContainerInterface $c){
        return new SignInAction($c->get(AuthProviderInterface::class));
    },

    RegisterAction::class => function(ContainerInterface $c){
        return new RegisterAction($c->get(UserServiceInterface::class));
    },

    RefreshAction::class => function(ContainerInterface $c){
        return new RefreshAction($c->get(AuthProviderInterface::class));
    },

    ValidateAction::class => function(ContainerInterface $c){
        return new ValidateAction($c->get(AuthProviderInterface::class));
    },

    GetPlayerIDAction::class => function(ContainerInterface $c){
        return new GetPlayerIDAction($c->get(AuthProviderInterface::class));
    }

];