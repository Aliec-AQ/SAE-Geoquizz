<?php

namespace geoquizz\core\services;

use geoquizz\core\dto\GameDTO;

interface GameServiceInterface
{
    public function createGame(string $idserie, string $idUser) : GameDTO;
    public function getPublicSequences(): array;
    public function changeSequenceStatus(string $idSequence): void;
    public function finishGame(string $idGame, int $score): void;
    public function historiqueGames(string $userId): array;
    public function gameById(string $id): GameDTO;
    public function replaySequence(string $idSequence, ?string $idUser): GameDTO;
    public function getHighscore(string $idSerie, string $idUser): int;

}