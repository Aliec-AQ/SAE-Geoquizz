<?php

namespace geoquizz\application\actions;

use Exception;
use geoquizz\core\dto\InputPlayerDTO;
use geoquizz\core\services\AuthorisationServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;

class PostPlayerAction extends AbstractAction
{
    private AuthorisationServiceInterface $authorisationService;

    public function __construct(AuthorisationServiceInterface $authorisationService){
        $this->authorisationService = $authorisationService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $params = $rq->getParsedBody() ?? null;

        if (!isset($params['id']) ) {
            throw new HttpBadRequestException($rq, 'Paramètres manquants');
        }

        $pseudo = null;

        if(isset($params['pseudo'])){
            $pseudo = $params['pseudo'];
        }

        try {
            $inputPlayerDTO= new InputPlayerDTO($params['id'],$params['pseudo']);
            $this->authorisationService->creationPlayer($inputPlayerDTO);
        }catch (Exception $e){
            throw new HttpInternalServerErrorException($rq,$e->getMessage());
        }

        return $rs->withStatus(201);
    }
}