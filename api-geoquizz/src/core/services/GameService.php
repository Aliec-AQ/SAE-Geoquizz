<?php

namespace core\services;

use core\repositoryInterfaces\GameRepositoryInterface;

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