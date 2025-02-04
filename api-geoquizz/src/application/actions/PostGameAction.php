<?php

namespace application\actions;

use core\services\GameServiceInterface;
use gateway\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostGameAction extends AbstractAction
{

    private GameServiceInterface $game_service;

    public function __construct(GameServiceInterface $game_service)
    {
        $this->game_service = $game_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idserie = $rq->getQueryParams()['idserie'];
        
        $token = "faire la gestion token";
        $game = $this->game_service->createGame($idserie);
        
        $res = [
            "token" => $token,
            "game" => $game
        ];
        
        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}