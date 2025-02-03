<?php

use gateway\application\actions\GeneriqueAuthnAction;
use gateway\application\actions\GeneriqueGeoQuizzAction;
use gateway\application\actions\GeneriqueMapAction;
use gateway\application\actions\GeneriqueUsersAction;
use gateway\application\middleware\AuthMiddleware;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
return [

    'client_geoquizz' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://api.auth.geoquizz:80']);
    },

    'client_map' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://api.map.geoquizz:80']);
    },

    'client_authn' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://api.auth.toubeelib:80']);
    },

    GeneriqueGeoQuizzAction::class => function (ContainerInterface $c){
        return new GeneriqueGeoQuizzAction($c->get('client_geoquizz'));
    },

    GeneriqueMapAction::class => function (ContainerInterface $c){
        return new GeneriqueMapAction($c->get('client_map'));
    },

    GeneriqueAuthnAction::class => function (ContainerInterface $c){
      return new GeneriqueAuthnAction($c->get('client_authn'));
    },

    AuthMiddleware::class => function (ContainerInterface $c) {
        return new AuthMiddleware($c->get('client_auth'));
    },


];