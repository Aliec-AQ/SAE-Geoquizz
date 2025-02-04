<?php

class PostGameAction extends AbstractAction
{

    private GameServiceInterface $game_service;

    public function __construct(GameServiceInterface $game_service)
    {
        $this->game_service = $game_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): \Psr\Http\Message\ResponseInterface
    {
        // TODO: Implement __invoke() method.
    }
}