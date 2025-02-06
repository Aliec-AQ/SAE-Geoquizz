<?php

namespace geoquizz_auth\application\actions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use geoquizz_auth\core\dto\InputUserDTO;
use geoquizz_auth\core\services\user\UserServiceInterface;
use geoquizz_auth\application\providers\auth\AuthProviderInterface;
use Slim\Exception\HttpUnauthorizedException;

class RegisterAction extends AbstractAction
{
    private UserServiceInterface $utilisateurService;
    private AuthProviderInterface $authProvider;

    public function __construct(UserServiceInterface $serviceUtilisateur, AuthProviderInterface $authProvider)
    {
        $this->utilisateurService = $serviceUtilisateur;
        $this->authProvider = $authProvider;
    }

  /**
   * @throws Exception
   */
  public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $params = $rq->getParsedBody() ?? null;

        if (!isset($params['email']) || !isset($params['mdp']) ) {
            throw new HttpBadRequestException($rq, 'Paramètres manquants');
        }

        $pseudo = null;

        if(isset($params['pseudo'])){
           $pseudo = $params['pseudo'];
        }

        $email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);

        try{
            $this->utilisateurService->createUser(new InputUserDTO($email, $params['mdp'],$pseudo));
        }catch (Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        try {
            $authRes = $this->authProvider->signIn(new InputUserDTO($email, $params['mdp'],$pseudo));
          }catch (Exception $e){
            throw new HttpUnauthorizedException($rq, 'Identifiants incorrects ' . $e->getMessage());
          }
      
          $response = [
            'type' => 'ressource',
            'atoken' => $authRes->accessToken,
            'rtoken' => $authRes->refreshToken,
            'role' => $authRes->role
          ];
      
          $rs->getBody()->write(json_encode($response));
      
          return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}