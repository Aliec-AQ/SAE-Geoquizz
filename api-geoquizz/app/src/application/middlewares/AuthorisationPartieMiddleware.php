<?php

namespace geoquizz\application\middlewares;

use geoquizz\application\providers\auth\JWTManager;
use geoquizz\core\services\TokenPartieServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;


class AuthorisationPartieMiddleware
{
    private JWTManager $jwtManager;
    private TokenPartieServiceInterface $tokenPartieService;

    public function __construct(JWTManager $jwtManager, TokenPartieServiceInterface $tokenPartieService)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenPartieService = $tokenPartieService;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {

        $routeContext = RouteContext::fromRequest($rq);
        $route = $routeContext->getRoute();
        $routeName = $route->getName();

        $h = $rq->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Bearer %s")[0];
        $tokenData = $this->jwtManager->decodeToken($tokenstring);
        $partieId = $tokenData['sub'];


        $response = $next->handle($rq);
        return $response;

    }
}