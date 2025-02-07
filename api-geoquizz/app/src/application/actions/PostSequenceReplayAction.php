<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;

class PostSequenceReplayAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gs) {
        $this->gameService = $gs;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idSequence = $rq->getQueryParams()['idSequence'];
        $idUser = $rq->getAttribute('playerID');

        if (!isset($idSequence)) {
            throw new HttpBadRequestException($rq, 'id de la séquence manquant dans la requête');
        }

        if (! (Validator::uuid()->validate($idSequence))) {
            throw new HttpBadRequestException($rq, "l'uuid de la séquence n'est pas valide");
        }

        try {
            $game = $this->gameService->replaySequence($idSequence, $idUser);
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $res = [
            'game' => $game
        ];

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}