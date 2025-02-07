<?php

namespace geoquizz\application\actions;

use geoquizz\application\providers\tokenPartie\TokenPartieProviderInterface;
use geoquizz\core\repositoryInterfaces\RabbitMQRepositoryInterface;
use geoquizz\core\services\AuthorisationServiceInterface;
use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;

class PostGameAction extends AbstractAction
{

    private GameServiceInterface $game_service;
    private TokenPartieProviderInterface $token_partie;

    private RabbitMQRepositoryInterface $MQRepository;

    private AuthorisationServiceInterface $authorisationService;

    public function __construct(GameServiceInterface $game_service, TokenPartieProviderInterface $token_part, RabbitMQRepositoryInterface $MQRepository, AuthorisationServiceInterface $authorisation)
    {
        $this->game_service = $game_service;
        $this->token_partie = $token_part;
        $this->MQRepository = $MQRepository;
        $this->authorisationService = $authorisation;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $idserie = $rq->getQueryParams()['idserie'];
        $idUser = $rq->getAttribute('playerID');

        if (!isset($idserie)) {
            throw new HttpBadRequestException($rq, 'id de la série manquant dans la requête');
        }

        if (! (Validator::uuid()->validate($idserie))) {
            throw new HttpBadRequestException($rq, "l'uuid de la série n'est pas valide");
        }

        if (! (Validator::uuid()->validate($idUser))) {
            throw new HttpBadRequestException($rq, "l'uuid de l'utilisateur n'est pas valide");
        }


        try {
            $game = $this->game_service->createGame($idserie, $idUser);
            $token = $this->token_partie->createTokenPartie($game->id);
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }


        $res = [
            "token" => $token,
            "game" => $game
        ];

        try {
            $mail = $this->authorisationService->playerEmail($idUser);
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $msg= [
            'action' => 'newGame',
            'mail' => $mail,
        ];

        try {
            $this->MQRepository->publish($msg,'game');
        }catch (\Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}