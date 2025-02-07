<?php

namespace geoquizz\application\middlewares;

use Exception;
use geoquizz\application\providers\tokenPartie\JWTManager;
use geoquizz\application\providers\tokenPartie\TokenPartieProviderInterface;
use geoquizz\core\services\AuthorisationServiceInterface;
use geoquizz\core\services\TokenPartieServiceInterface;
use geoquizz\core\services\TokenServiceException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;


class AuthorisationReplayMiddleware
{
    protected AuthorisationServiceInterface $authorisationService;

    public function __construct(AuthorisationServiceInterface $authorisationService)
    {
        $this->authorisationService = $authorisationService;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {

        $routeContext = RouteContext::fromRequest($rq);
        $route = $routeContext->getRoute();
        $routeName = $route->getName();

        if (! $rq->hasHeader('Origin'))
            New HttpUnauthorizedException ($rq, "missing Origin Header (game)");
        if (! $rq->hasHeader("Authorization")){
            $rq = $rq->withAttribute('playerID', null);
        }else{
            $h = $rq->getHeader('Authorization')[0];
            if($h == null || empty($h)){
                throw new HttpUnauthorizedException($rq,"erreur Authorization");
            }else{
                $tokenstring = sscanf($h, "Bearer %s")[0];
                try {
                    $playerID = $this->authorisationService->playerID($tokenstring);
                    $rq = $rq->withAttribute('playerID', $playerID);
                }catch (Exception $e){
                    throw new HttpBadRequestException($rq,"erreur lors de la récupération de l'idplayer");
                }

            }
        }

        $response = $next->handle($rq);
        return $response;

    }
}