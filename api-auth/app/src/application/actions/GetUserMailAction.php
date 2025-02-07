<?php

namespace geoquizz_auth\application\actions;

use geoquizz_auth\core\services\user\UserServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetUserMailAction extends AbstractAction
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService){
        $this->userService = $userService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $playerId = $rq->getQueryParams()['playerid'];

        $user = $this->userService->findUserById($playerId);

        $response = [
            'type' => 'ressource',
            'mail' => $user->email,
        ];

        $rs->getBody()->write(json_encode($response));

        return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');

    }
}