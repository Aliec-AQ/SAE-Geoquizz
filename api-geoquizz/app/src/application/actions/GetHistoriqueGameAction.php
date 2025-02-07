<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;

class GetHistoriqueGameAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gs)
    {
        $this->gameService = $gs;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $userId = $rq->getAttribute('playerID');

        if (!(Validator::uuid()->validate($userId))) {
            throw new HttpBadRequestException($rq, "l'uuid de l'utilisateur n'est pas valide");
        }

        try {
            $games = $this->gameService->historiqueGames($userId);
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $res = [
            'games' => $games
        ];

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}