<?php

namespace geoquizz\core\services;

class AuthorisationService implements AuthorisationServiceInterface
{
    private $authRepository;

    public function __construct($authRepository){
        $this->authRepository = $authRepository;
    }

    public function playerID(string $token): string
    {
        return $this->authRepository->RecuperationIDPlayer($token);
    }
}