<?php

namespace geoquizz\core\repositoryInterfaces;


use geoquizz\core\domain\entity\Game;
use geoquizz\core\domain\entity\Sequence;
use Ramsey\Uuid\Uuid;

interface GameRepositoryInterface
{
    public function createSequence(string $idserie, array $images) : Sequence;
    public function createGame(Sequence $sequence,string $playerId) : Game;

    public function findGameByIdForAuthorisation(int $id): Game;

    public function findSequenceById($sequence_id): Sequence;

    public function getIDPhotosByIDSequence(string $sequenceID): array;
}