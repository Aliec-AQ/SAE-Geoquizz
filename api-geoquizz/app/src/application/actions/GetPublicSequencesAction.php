<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

class GetPublicSequencesAction extends AbstractAction
{

    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gameService)
    {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $publicSequences = $this->gameService->getPublicSequences();
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $res = [
            "sequences_publiques" => $publicSequences
        ];

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}