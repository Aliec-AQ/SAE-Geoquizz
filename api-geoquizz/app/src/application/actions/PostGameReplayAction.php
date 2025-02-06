<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostGameReplayAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gs) {
        $this->gameService = $gs;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idSequence = $rq->getQueryParams()['idSequence'];
        $idUser = $rq->getAttribute('playerID');
        $game = $this->gameService->replayGame($idSequence, $idUser);

        $res = [
            'game' => $game
        ];

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}