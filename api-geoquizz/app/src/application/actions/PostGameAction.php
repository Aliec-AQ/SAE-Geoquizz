<?php

namespace geoquizz\application\actions;

use geoquizz\application\providers\tokenPartie\TokenPartieProviderInterface;
use geoquizz\core\repositoryInterfaces\RabbitMQRepositoryInterface;
use geoquizz\core\services\AuthorisationServiceInterface;
use geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        $game = $this->game_service->createGame($idserie, $idUser);

        $token = $this->token_partie->createTokenPartie($game->id);
        
        $res = [
            "token" => $token,
            "game" => $game
        ];

        $mail = $this->authorisationService->playerEmail($idUser);

        $msg= [
            'action' => 'newGame',
            'mail' => $mail
        ];

        $this->MQRepository->publish($msg,'game');

        $rs->getBody()->write(json_encode($res));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}