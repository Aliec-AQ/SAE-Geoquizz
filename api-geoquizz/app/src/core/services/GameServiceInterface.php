<?php

namespace geoquizz\core\services;

use geoquizz\core\dto\GameDTO;

interface GameServiceInterface
{
    public function createGame(string $idserie, string $idUser) : GameDTO;
    public function getPublicSequences(): array;
    public function changeSequenceStatus(string $idSequence): void;
}