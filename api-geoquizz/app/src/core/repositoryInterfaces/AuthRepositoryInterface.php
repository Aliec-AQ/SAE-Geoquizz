<?php

namespace geoquizz\core\repositoryInterfaces;

interface AuthRepositoryInterface
{
    public function RecuperationIDPlayer(string $token): string;
}