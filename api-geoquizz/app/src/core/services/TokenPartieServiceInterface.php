<?php

namespace geoquizz\core\services;

interface TokenPartieServiceInterface
{
    public function verifyPartie(string $token);
}