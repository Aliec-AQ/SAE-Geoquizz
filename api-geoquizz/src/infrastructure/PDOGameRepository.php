<?php

namespace infrastructure;

use core\repositoryInterfaces\Game;
use core\repositoryInterfaces\GameRepositoryInterface;
use core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PDOGameRepository implements GameRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createGame($idserie): Sequence
    {
        $stmt = $this->pdo->prepare('INSERT INTO games () VALUES () RETURNING id');
        $stmt->bindParam(1, $idserie);
        $stmt->execute();
        $row = $stmt->fetch();

        if($row) {
            $sequence = new Sequence(

            );
            $sequence->setId($row['id']);
            return $sequence;
        } else {
            throw new RepositoryEntityNotFoundException("Game not found");
        }
    }
}