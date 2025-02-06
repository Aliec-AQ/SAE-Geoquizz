<?php

namespace geoquizz\core\services;

use geoquizz\core\dto\InputPlayerDTO;

interface AuthorisationServiceInterface
{
    public function  playerID(string $token):string;

    public function creationPlayer(InputPlayerDTO $ipd): void;
}