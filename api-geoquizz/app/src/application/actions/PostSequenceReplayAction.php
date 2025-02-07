<?php

namespace geoquizz\application\actions;

use Exception;
use geoquizz\application\providers\tokenPartie\TokenPartieProviderInterface;
use geoquizz\core\repositoryInterfaces\RabbitMQRepositoryInterface;
use geoquizz\core\services\AuthorisationServiceInterface;
use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;

class PostSequenceReplayAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    private RabbitMQRepositoryInterface $MQRepository;

    private AuthorisationServiceInterface $authorisationService;

    private TokenPartieProviderInterface $token_partie;

    public function __construct(GameServiceInterface $gs,  RabbitMQRepositoryInterface $MQRepository, AuthorisationServiceInterface $authorisation, TokenPartieProviderInterface $token_part) {
        $this->gameService = $gs;
        $this->MQRepository = $MQRepository;
        $this->authorisationService = $authorisation;
        $this->token_partie = $token_part;
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
            $token = $this->token_partie->createTokenPartie($game->id);
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $res = [
            "token" => $token,
            'game' => $game
        ];

        if($idUser != null){
            try{
                $mail = $this->authorisationService->playerEmail($idUser);
            }catch (Exception $e){
                throw new HttpBadRequestException($rq,"erreur lors de la recuperation du mail : ". $e->getMessage());
            }

            $msg= [
                'action' => 'replayGame',
                'mail' => $mail,
            ];

            try {
                $this->MQRepository->publish($msg,'game');
            }catch (\Exception $e) {
                throw new HttpBadRequestException($rq, "erreur lors de l envoi de msg : ". $e->getMessage());
            }
        }

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}