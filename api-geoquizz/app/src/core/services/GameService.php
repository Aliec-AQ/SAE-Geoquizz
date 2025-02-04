<?php

namespace geoquizz\core\services;

use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\dto\GameDTO;
use geoquizz\core\services\MapsRepositoryInterface;

class GameService implements GameServiceInterface
{
    private GameRepositoryInterface $gameRepository;
    private MapsRepositoryInterface $MapsRepository;

    public function createGame($idserie): GameDTO
    {
        $sequence = $this->gameRepository->createSequence($idserie);
        $imagesInfos = $this->MapsRepository->getImagesInfos($idserie);
        $gameDTO = new GameDTO();
        return $gameDTO;
    }
}