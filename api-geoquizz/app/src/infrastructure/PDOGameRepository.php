<?php

namespace geoquizz\infrastructure;

use Faker\Factory;
use geoquizz\core\domain\entity\Game;
use geoquizz\core\domain\entity\Photo;
use geoquizz\core\domain\entity\Player;
use geoquizz\core\repositoryInterfaces\GameRepositoryException;
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
        try {
            $stmt = $this->pdo->prepare('INSERT INTO sequences (serie_id) VALUES (?) RETURNING id');
            $stmt->bindParam(1, $idserie);
            $stmt->execute();
            $row = $stmt->fetch();
        }catch(\Exception $e){
            throw new GameRepositoryException("Impossible de créer la séquence");
        }

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

    public function createGame(Sequence $sequence,?string $playerId): Game
    {
        try {
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
        } catch (\Exception $e) {
            throw new GameRepositoryException("Impossible de créer la partie");
        }


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

    public function findGameByIdForAuthorisation(string $id): Game{
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM players_sequences WHERE id =?');
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch();
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver la partie");
        }

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
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM sequences WHERE id =?');
            $stmt->bindParam(1, $sequence_id);
            $stmt->execute();
            $row = $stmt->fetch();
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver la séquence");
        }

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
        try {
            $stmt = $this->pdo->prepare('SELECT photo_id FROM photos_sequences WHERE sequence_id =? ORDER BY "order" ASC');
            $stmt->bindParam(1, $sequenceID);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $ids = [];
            foreach ($rows as $row) {
                $ids[] = $row['photo_id'];
            }
            return $ids;
        }catch(\Exception $e){
            throw new GameRepositoryException("Impossible de trouver les photos de la séquence");
        }
    }

    public function getPublicSequences(): array
    {
        try {
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
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver les séquences publiques");
        }
    }

    public function getHighScore(array $sequences): array
    {
        try {
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
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver les scores");
        }
    }

    public function ajouterPlayer(Player $p) : void{

        try {
            $idPlayer = $p->getID();
            $pseudo = $p->pseudo;
            if($pseudo == null || $pseudo == ''){
                $faker = Factory::create('fr_FR');
                $pseudo = $faker->userName;
            }
            $date = date('Y-m-d H:i:s');

            $stmt = $this->pdo->prepare('INSERT INTO players (id_user,pseudo,last_connection) VALUES (?,?,?)');
            $stmt->bindParam(1, $idPlayer);
            $stmt->bindParam(2, $pseudo);
            $stmt->bindParam(3, $date);
            $stmt->execute();
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible d'ajouter le joueur");
        }
    }

    public function changeSequenceStatus(string $idSequence): void
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE sequences SET public = true WHERE id = ?');
            $stmt->bindParam(1, $idSequence);
            $stmt->execute();
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de changer le status de la séquence");
        }
    }

    public function finishGame(string $idGame, $score): string
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE players_sequences SET score = ?, status = true WHERE id = ? RETURNING player_id');
            $stmt->bindParam(1, $score);
            $stmt->bindParam(2, $idGame);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row['player_id'];
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de finir la partie");
        }
    }

    public function historiqueGames(string $userId): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM players_sequences WHERE player_id = ? ORDER BY date DESC');
            $stmt->bindParam(1, $userId);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $gamesHistorique = [];
            foreach ($rows as $row) {
                $gamesHistorique[] = [
                    "id" => $row['id'],
                    "score" => $row['score'],
                ];
            }
            return $gamesHistorique;
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver l'historique des parties");
        }
    }

    public function gameById(string $id): Game
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM players_sequences INNER JOIN sequences ON players_sequences.sequence_id = sequences.id WHERE players_sequences.id = ?');
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch();

            $sequence = new Sequence($row['public'], $row['serie_id']);
            $sequence->setId($row['sequence_id']);
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver la partie");
        }

        if($row) {
            $game = new Game(
                $row['player_id'],
                $row['serie_id'],
                $sequence,
                $row['score'],
                $row['status']
            );
            $game->setId($row['id']);
            return $game;
        } else {
            throw new RepositoryEntityNotFoundException("Game not found");
        }
    }

    public function getSequenceById(?string $id): Sequence
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM sequences WHERE id = ?');
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch();
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver la séquence");
        }

        if($row) {
            $sequence = new Sequence(
                $row['public'],
                $row['serie_id']
            );
            $sequence->setId($row['id']);
            return $sequence;
        } else {
            throw new RepositoryEntityNotFoundException("Sequence not found");
        }
    }

    public function getHighscoreForUserBySerie(string $idSerie, string $idUser): int
    {
        try {
            $stmt = $this->pdo->prepare('SELECT score FROM sequences INNER JOIN players_sequences ON sequences.id = players_sequences.sequence_id WHERE sequences.serie_id = ? ORDER BY score DESC LIMIT 1');
            $stmt->bindParam(1, $idSerie);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row['score'];
        }catch (\Exception $e){
            throw new GameRepositoryException("Impossible de trouver le highscore");
        }
    }
}