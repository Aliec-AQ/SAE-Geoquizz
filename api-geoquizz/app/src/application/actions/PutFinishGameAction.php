<?php

namespace geoquizz\application\actions;

use geoquizz\application\providers\auth\TokenPartieProvider;
use geoquizz\application\providers\auth\TokenPartieProviderInterface;
use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PutFinishGameAction extends AbstractAction
{
    private GameServiceInterface $gameService;
    public function __construct(GameServiceInterface $gs){
        $this->gameService = $gs;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idGame = $rq->getAttribute('idGame');
        $score = $rq->getAttribute('score');
        $this->gameService->finishGame($idGame, $score);
    }
}