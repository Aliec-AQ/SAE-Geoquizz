<?php

namespace geoquizz\core\services;

use geoquizz\core\services\GameDTO;

interface GameServiceInterface
{
    public function createGame($idserie) : GameDTO;
}