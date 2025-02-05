<?php

namespace geoquizz_auth\application\actions;

use geoquizz_auth\application\providers\auth\AuthProviderInterface;
use PHPUnit\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

class GetPlayerIDAction extends AbstractAction
{

    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider){
        $this->authProvider = $authProvider;
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $playerId = "";
        try{
            $headers = $rq->getHeader('Authorization');
            $tokenstring = sscanf($headers[0], "Bearer %s")[0];
            $playerId = $this->authProvider->getPlayerID($tokenstring);
        }catch (Exception $e){
            throw new HttpBadRequestException($rq,"erreur lors de la récupération de l'id");
        }

        $response = [
            'type' => 'ressource',
            'playerid' => $playerId,
        ];

        $rs->getBody()->write(json_encode($response));

        return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}