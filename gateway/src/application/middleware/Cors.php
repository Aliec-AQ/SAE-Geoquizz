<?php

namespace gateway\application\middleware;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class Cors{

    private $allowedOrigins = [
        'http://localhost:35633',
        'http://docketu.iutnc.univ-lorraine.fr:35633'
    ];

    /**
     * GERE LES REQUETES CORS
     * @param ServerRequestInterface $rq
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface {
        $path = $rq->getUri()->getPath();

        // Permettre la récupération des assets de bypass le CORS (car src="gateway/assets/..." n'a pas d'origin)
        if (strpos($path, '/assets/') === 0) {
            return $next->handle($rq);
        }

        if (!$rq->hasHeader('Origin')) {
            throw new HttpUnauthorizedException($rq, "missing Origin Header (cors)");
        }

        $origin = $rq->getHeaderLine('Origin');
        if (!in_array($origin, $this->allowedOrigins)) {
            throw new HttpUnauthorizedException($rq, "Origin not allowed (cors)");
        }

        $response = $next->handle($rq);

        $response = $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Methods', 'POST, PUT, GET, PATCH')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type')
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}