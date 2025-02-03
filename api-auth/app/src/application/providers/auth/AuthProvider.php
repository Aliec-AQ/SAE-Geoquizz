<?php
namespace geoquizz_auth\application\providers\auth;

use geoquizz_auth\core\dto\InputUserDTO;
use geoquizz_auth\core\dto\UserDTO;
use geoquizz_auth\core\services\auth\AuthServiceException;
use geoquizz_auth\core\services\auth\AuthServiceInterface;
use geoquizz_auth\application\providers\auth\JWTManager;

class AuthProvider implements AuthProviderInterface
{
    
    private AuthServiceInterface $authService;
    private JWTManager $jwtManager;

    public function __construct(AuthServiceInterface $authService, JWTManager $jwtManager)
    {
        $this->authService = $authService;
        $this->jwtManager = $jwtManager;
    }

    public function signIn(InputUserDTO $credentials): UserDTO
    {
        try{
            // Verifier les credentials
            $authDTO = $this->authService->verifyCredentials($credentials);

            // Creer le payload pour les tokens
            $payload = [
                'iat'=>time(),
                'exp'=>time()+3600,
                'sub' => $authDTO->id,
                'data' => [
                    'role' => $authDTO->role,
                    'email' => $authDTO->email,
                    'pseudo' => $authDTO->pseudo
                ]
            ];

            // Creer les tokens
            $accessToken = $this->jwtManager->createAccessToken($payload);
            $refreshToken = $this->jwtManager->createRefreshToken($payload);

            // Retourner le AuthDTO avec les tokens
            return new UserDTO(
                $authDTO->id,
                $authDTO->email,
                $authDTO->role,
                $authDTO->pseudo,
                $accessToken,
                $refreshToken
            );
        }catch(\Exception $e){
            throw new AuthServiceException("erreur auth" . $e->getMessage());
        }
    }

    public function getSignIn(string $token): UserDTO{
        $arrayToken = $this->jwtManager->decodeToken($token);

        return new UserDTO(
            $arrayToken['sub'],
            $arrayToken['data']->email,
            $arrayToken['data']->role,
            $arrayToken['data']->pseudo
        );
    }

    public function refresh(string $token): string {
        $arrayToken = $this->jwtManager->decodeToken($token);
        $payload = [
            'iat'=>time(),
            'exp'=>time()+3600,
            'sub' => $arrayToken['sub'],
            'data' => [
                'role' => $arrayToken['data']->role,
                'email' => $arrayToken['data']->email,
                'pseudo' => $arrayToken['data']->pseudo
            ]
        ];

        $accessToken = $this->jwtManager->createAccessToken($payload);

        return $accessToken;
    }
}