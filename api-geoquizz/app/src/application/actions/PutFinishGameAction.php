<?php

namespace geoquizz\application\actions;

use Exception;
use geoquizz\core\repositoryInterfaces\RabbitMQRepositoryInterface;
use geoquizz\core\services\AuthorisationServiceInterface;
use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;

class PutFinishGameAction extends AbstractAction
{
    private GameServiceInterface $gameService;
    private RabbitMQRepositoryInterface $rabbitMQRep;

    private AuthorisationServiceInterface $authorisationService;

    public function __construct(GameServiceInterface $gs, RabbitMQRepositoryInterface $rabbitMQRep, AuthorisationServiceInterface $authorisationService){
        $this->gameService = $gs;
        $this->rabbitMQRep = $rabbitMQRep;
        $this->authorisationService = $authorisationService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idGame = $rq->getAttribute('idGame');
        $param = $rq->getQueryParams()['score'];
        $score = intval($param);

        if (!isset($score) || $score < 0) {
            throw new HttpBadRequestException($rq, 'score manquant ou invalide');
        }

        if (! (Validator::uuid()->validate($idGame))) {
            throw new HttpBadRequestException($rq, "l'uuid de la partie n'est pas valide");
        }

        if (! (Validator::intVal()->validate($score))) {
            throw new HttpBadRequestException($rq, "le score n'est pas valide");
        }


        try {
            $player_id = $this->gameService->finishGame($idGame, $score);
            $mail = $this->authorisationService->playerEmail($player_id);
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }


        $msg = [
            'action' => 'finish_game',
            'mail' => $mail,
            'score' => $score
        ];

        try{
            $this->rabbitMQRep->publish($msg, 'game');
        }catch (Exception $e){
            throw new HttpBadRequestException($rq,'erreur lors de l envoi de msg '. $e->getMessage());
        }

        return $rs->withHeader('Content-Type', 'application/json');
    }
}