<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;

class GetHighscoreAction extends AbstractAction
{

    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gs)
    {
        $this->gameService = $gs;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idSerie = $args['id'];
        $idUser = $rq->getAttribute('playerID');

        if (!(Validator::uuid()->validate($idSerie))) {
            throw new HttpBadRequestException($rq, "l'uuid de la série n'est pas valide");
        }

        if (!(Validator::uuid()->validate($idUser))) {
            throw new HttpBadRequestException($rq, "l'uuid de l'utilisateur n'est pas valide");
        }

        try {
            $highscore = $this->gameService->getHighscore($idSerie, $idUser);
        } catch (\Exception $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $res = [
            'highscore' => $highscore
        ];

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}