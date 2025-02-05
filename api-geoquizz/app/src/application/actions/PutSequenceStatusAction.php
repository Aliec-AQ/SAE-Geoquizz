<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PutSequenceStatusAction extends AbstractAction
{

    private GameServiceInterface $gameService;

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idSequence = $args['idSequence'];

        $this->gameService->changeSequenceStatus($idSequence);
        return $rs->withHeader('Content-Type', 'application/json');
    }
}