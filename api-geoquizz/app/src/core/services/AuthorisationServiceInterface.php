<?php

namespace geoquizz\core\services;

interface AuthorisationServiceInterface
{
    public function  playerID(string $token):string;
}