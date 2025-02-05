<?php

namespace geoquizz\infrastructure;

use geoquizz\core\domain\entity\Game;
use geoquizz\core\domain\entity\Photo;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\domain\entity\Sequence;
use PDO;


class PDOGameRepository implements GameRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createSequence(string $idserie, array $images): Sequence
    {
        $stmt = $this->pdo->prepare('INSERT INTO sequences (serie_id) VALUES (?) RETURNING id');
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

    public function createGame(Sequence $sequence,string $playerId): Game
    {
        $idSequence = $sequence->getId();
        $order = 0;
        foreach ($sequence->photos as $photo) {
            $idPhoto = $photo->getId();
            if ($idPhoto === null) {
                throw new \Exception("Erreur: une photo n'a pas d'ID.");
            }

            $stmt = $this->pdo->prepare('INSERT INTO "photos_sequences" ("photo_id","order","sequence_id" ) VALUES (?, ?, ?)');

            $stmt->bindParam(1, $idPhoto);
            $stmt->bindParam(2, $order);
            $stmt->bindParam(3, $idSequence);
            $stmt->execute();
            $order++;
        }

        $date = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare('INSERT INTO players_sequences (player_id, sequence_id, date) VALUES (?,?,?) RETURNING id');
        $stmt->bindParam(1, $playerId);
        $stmt->bindParam(2, $idSequence);
        $stmt->bindParam(3, $date);
        $stmt->execute();
        $row = $stmt->fetch();

        if($row) {
            $game = new Game(
                $playerId,
                $sequence->serie_id,
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

    public function findGameByIdForAuthorisation(int $id): Game{
        $stmt = $this->pdo->prepare('SELECT * FROM players_sequences WHERE id =?');
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();

        if($row) {
            $game = new Game(
                $row['player_id'],
                null,
                null,
                $row['score'],
                $row['status']
            );
            $game->setId($row['id']);
            return $game;
        } else {
            throw new RepositoryEntityNotFoundException("Game not found");
        }
    }

    public function findSequenceById($sequence_id): Sequence
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sequences WHERE id =?');
        $stmt->bindParam(1, $sequence_id);
        $stmt->execute();
        $row = $stmt->fetch();

        if($row) {
            $sequence = new Sequence(
                $row['public'],
                $row['serie_id'],
            );
            $sequence->setId($row['id']);
            return $sequence;
        } else {
            throw new RepositoryEntityNotFoundException("Sequence not found");
        }
    }

    public function getIDPhotosByIDSequence(string $sequenceID): array{
        $stmt = $this->pdo->prepare('SELECT photo_id FROM photos_sequences WHERE sequence_id =? ORDER BY order ASC');
        $stmt->bindParam(1, $sequenceID);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row['photo_id'];
        }
        return $ids;
    }

    public function getPublicSequences(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sequences WHERE public = true');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $sequences = [];
        foreach ($rows as $row) {
            $sequence = new Sequence(
                $row['public'],
                $row['serie_id']
            );
            $sequence->setId($row['id']);
            $sequences[] = $sequence;
        }
        return $sequences;
    }

    public function getHighScore(array $sequences): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM players_sequences WHERE sequence_id =? ORDER BY score DESC LIMIT 1');
        $scores = [];

        foreach ($sequences as $sequence) {
            $idSequence =$sequence->getId();
            $stmt->bindParam(1, $idSequence);
            $stmt->execute();
            $row = $stmt->fetch();
            $scores [$row['sequence_id']] = $row['score'];
        }
        return $scores;
    }

    public function ajouterPlayer(string $idplayer) : void{
        $stmt = $this->pdo->prepare('INSERT INTO players (id) VALUES (?)');
        $stmt->bindParam(1, $idplayer);
        $stmt->execute();
    }

    public function changeSequenceStatus(string $idSequence): void
    {
        $stmt = $this->pdo->prepare('UPDATE sequences SET public = true WHERE id = ?');
        $stmt->bindParam(1, $idSequence);
        $stmt->execute();
    }

    public function finishGame(string $idGame, $score): void
    {
        $stmt = $this->pdo->prepare('UPDATE players_sequences SET score = ?, status = true WHERE id = ?');
        $stmt->bindParam(1, $score);
        $stmt->bindParam(2, $idGame);
        $stmt->execute();
    }
}