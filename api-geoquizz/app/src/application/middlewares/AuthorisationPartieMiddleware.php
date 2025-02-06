<?php

namespace geoquizz\application\middlewares;

use Exception;
use geoquizz\application\providers\tokenPartie\JWTManager;
use geoquizz\application\providers\tokenPartie\TokenPartieProviderInterface;
use geoquizz\core\services\TokenPartieServiceInterface;
use geoquizz\core\services\TokenServiceException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;


class AuthorisationPartieMiddleware
{
    private JWTManager $jwtManager;
    private TokenPartieServiceInterface $tokenPartieService;

    private TokenPartieProviderInterface $tokenPartieProvider;

    public function __construct(JWTManager $jwtManager, TokenPartieServiceInterface $tokenPartieService, TokenPartieProviderInterface $tokenPartieProvider)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenPartieService = $tokenPartieService;
        $this->tokenPartieProvider = $tokenPartieProvider;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {

        $routeContext = RouteContext::fromRequest($rq);
        $route = $routeContext->getRoute();
        $routeName = $route->getName();

        if (! $rq->hasHeader('Origin'))
            New HttpUnauthorizedException ($rq, "missing Origin Header (auth)");
        if (! $rq->hasHeader("Authorization")){
            New HttpUnauthorizedException ($rq, "missing Authorization Header (auth)");
        }

        $h = $rq->getHeader('Authorization')[0];
        if($h == null || empty($h)){
            throw new HttpUnauthorizedException($rq,"no auth, try /users/signin[/] or /users/signup[/]");
        }
        $tokenstring = sscanf($h, "Bearer %s")[0];
        $partieId = $this->tokenPartieProvider->getTokenPartie($tokenstring);
        $rq = $rq->withAttribute('idGame', $partieId);

        try{
            $this->tokenPartieService->verifyPartie($partieId);
        }catch (TokenServiceException $e){
            throw new HttpBadRequestException($rq,"Invalide put : ".$e->getMessage());
        }catch(Exception $e){
            throw new HttpInternalServerErrorException($rq,"Erreur put game");
        }

        $response = $next->handle($rq);
        return $response;

    }
}