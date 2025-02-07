<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Respect\Validation\Validator;

class GetGameByIdAction extends AbstractAction
{

    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gs)
    {
        $this->gameService = $gs;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idGame = $rq->getAttribute('idGame');

        if (!(Validator::uuid()->validate($idGame))) {
            throw new HttpBadRequestException($rq, "l'uuid de la partie n'est pas valide");
        }


        try {
            $game = $this->gameService->gameById($idGame);
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