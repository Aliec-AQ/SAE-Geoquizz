<?php
namespace geoquizz\application\providers\auth;

use geoquizz\core\services\TokenPartieService;

use geoquizz\core\services\TokenPartieServiceInterface;

class TokenPartieProvider implements TokenPartieProviderInterface
{
    
    private TokenPartieServiceInterface $tokenPartieService;
    private JWTManager $jwtManager;

    public function __construct(TokenPartieServiceInterface $tokenPartieService , JWTManager $jwtManager)
    {
        $this->tokenPartieService = $tokenPartieService;
        $this->jwtManager = $jwtManager;
    }

    public function createTokenPartie(string $idPartie): string
    {
        try{

            $payload = [
                'iat'=>time(),
                'exp'=>time()+3600,
                'sub' => $idPartie
            ];

            /*
                'data' => [
                    'id' => $idPartie,
                ]
             */

            $tokenPartie = $this->jwtManager->createToken($payload);

            return $tokenPartie;
        }catch(\Exception $e){
            throw new TokenPartieProviderException("erreur auth" . $e->getMessage());
        }
    }

    public function getTokenPartie(string $token): string{
        $arrayToken = $this->jwtManager->decodeToken($token);
        return $arrayToken['sub'];
    }
}