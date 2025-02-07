<?php

namespace geoquizz\core\repositoryInterfaces;


use geoquizz\core\domain\entity\Game;
use geoquizz\core\domain\entity\Player;
use geoquizz\core\domain\entity\Sequence;
use Ramsey\Uuid\Uuid;

interface GameRepositoryInterface
{
    public function createSequence(string $idserie, array $images) : Sequence;
    public function createGame(Sequence $sequence,string $playerId) : Game;

    public function findGameByIdForAuthorisation(string $id): Game;

    public function findSequenceById($sequence_id): Sequence;

    public function getIDPhotosByIDSequence(string $sequenceID): array;

    public function getPublicSequences(): array;

    public function getHighScore(array $sequences): array;

    public function changeSequenceStatus(string $idSequence): void;
    public function finishGame(string $idGame, int $score): string;
    public function historiqueGames(string $userId): array;
    public function gameById(string $id): Game;
    public function getSequenceById(?string $id): Sequence;
    public function ajouterPlayer(Player $p) : void;
    public function getHighscoreForUserBySerie(string $idSerie, string $idUser): int;
}