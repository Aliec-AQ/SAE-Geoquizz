<?php

namespace geoquizz\infrastructure;

use geoquizz\core\domain\entity\Game;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\infrastructure\PDO;
use geoquizz\core\domain\entity\Sequence;


class PDOGameRepository implements GameRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createSequence(string $idserie, array $images): Sequence
    {
        $stmt = $this->pdo->prepare('INSERT INTO sequences (serie_id) VALUES ($idserie) RETURNING id');
        $stmt->bindParam(1, $idserie);
        $stmt->execute();
        $row = $stmt->fetch();

        if($row) {
            $sequence = new Sequence(
                false,
                $idserie,
                $images
            );
            $sequence->setId($row['id']);
            return $sequence;
        } else {
            throw new RepositoryEntityNotFoundException("Game not found");
        }
    }

    public function createGame(Sequence $sequence, $playerId): Game
    {
        $idSequence = $sequence->getId();
        $order = 0;
        foreach ($sequence->photos as $photo) {
            $order++;
            $idPhoto = $photo->getId();
            $stmt = $this->pdo->prepare('INSERT INTO photos_sequences (photo_id, order, sequence_id ) VALUES (?, ?, ?)');
            $stmt->bindParam(1, $idPhoto);
            $stmt->bindParam(2, $order);
            $stmt->bindParam(3, $idSequence);
            $stmt->execute();
        }

        $stmt = $this->pdo->prepare('INSERT INTO players_sequences (player_id, sequence_id, date) VALUES (?,?,?) RETURNING id');
        $stmt->bindParam(1, $playerId);
        $stmt->bindParam(2, $idSequence);
        $stmt->bindParam(3, date('Y-m-d H:i:s'));
        $stmt->execute();
        $row = $stmt->fetch();

        if($row) {
            $game = new Game(
                $playerId,
                $sequence->getSerieId(),
                $sequence,
                0,
                false
            );
            $game->setId($row['id']);
            return $game;
        } else {
            throw new RepositoryEntityNotFoundException("Game not found");
        }

    }
}