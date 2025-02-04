<?php

namespace geoquizz\core\services;

use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\dto\GameDTO;
use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;


class GameService implements GameServiceInterface
{
    private GameRepositoryInterface $gameRepository;
    private MapsRepositoryInterface $MapsRepository;

    public function createGame(string $idserie, string $idUser): GameDTO
    {
        $imagesInfos = $this->MapsRepository->getImagesInfos($idserie);
        $sequence = $this->gameRepository->createSequence($idserie);
        $sequenceImages = $this->gameRepository->createGame($sequence, $imagesInfos);
        $gameDTO = new GameDTO();
        return $gameDTO;
    }
}