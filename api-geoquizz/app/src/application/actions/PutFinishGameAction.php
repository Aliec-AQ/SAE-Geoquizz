<?php

namespace geoquizz\application\actions;

use Exception;
use geoquizz\core\repositoryInterfaces\RabbitMQRepositoryInterface;
use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

class PutFinishGameAction extends AbstractAction
{
    private GameServiceInterface $gameService;
    private RabbitMQRepositoryInterface $rabbitMQRep;

    public function __construct(GameServiceInterface $gs, RabbitMQRepositoryInterface $rabbitMQRep){
        $this->gameService = $gs;
        $this->rabbitMQRep = $rabbitMQRep;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idGame = $rq->getAttribute('idGame');
        $param = $rq->getQueryParams()['score'];
        $score = intval($param);
        $this->gameService->finishGame($idGame, $score);

        $msg = [
            'action' => 'finish_game',
            'idGame' => $idGame,
            'score' => $score
        ];

        try{
            $this->rabbitMQRep->publish(json_encode($msg), 'game');
        }catch (Exception $e){
            throw new HttpBadRequestException($rq,'erreur lors de l envoi de msg '. $e->getMessage());
        }

        return $rs->withHeader('Content-Type', 'application/json');
    }
}