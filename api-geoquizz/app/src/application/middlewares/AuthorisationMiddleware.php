<?php

namespace geoquizz\application\middlewares;

use Exception;
use geoquizz\application\providers\auth\TokenPartieProviderInterface;
use geoquizz\core\services\AuthorisationServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class AuthorisationMiddleware{

    protected AuthorisationServiceInterface $authorisationService;

    public function __construct(AuthorisationServiceInterface $authorisationService)
    {
        $this->authorisationService = $authorisationService;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface{
        $routeContext = RouteContext::fromRequest($rq);
        $route = $routeContext->getRoute();

        if (! $rq->hasHeader('Origin'))
            New HttpUnauthorizedException ($rq, "missing Origin Header (auth)");
        if (! $rq->hasHeader("Authorization")){
            New HttpUnauthorizedException ($rq, "missing Authorization Header (auth)");
        }
        if(!isset($rq->getHeader('Authorization')[0])){
            throw new HttpUnauthorizedException($rq,"no auth, try /utilisateur/signin[/] or /utilisateur/signup[/]");
        }
        if(strlen($rq->getHeader('Authorization')[0]) == 6){
            throw new HttpUnauthorizedException($rq,"no auth, try /utilisateur/signin[/] or /utilisateur/signup[/]");
        }

        try{
            $h = $rq->getHeader('Authorization')[0];
            $tokenstring = sscanf($h, "Bearer %s")[0];
        }catch (Exception $e){
            throw new HttpUnauthorizedException($rq,"token invalide");
        }
        $playerID = $this->authorisationService->playerID($tokenstring);
        $rq = $rq->withAttribute('playerID',$playerID);
        return $next->handle($rq);
    }

}