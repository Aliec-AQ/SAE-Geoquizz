<?php

namespace geoquizz\core\services;

use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\dto\GameDTO;
use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;


class GameService implements GameServiceInterface
{
    private GameRepositoryInterface $gameRepository;
    private MapsRepositoryInterface $MapsRepository;

    public function __construct(GameRepositoryInterface $gameRepository, MapsRepositoryInterface $mapsRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->mapsRepository = $mapsRepository;
    }

    public function createGame(string $idserie, string $idUser): GameDTO
    {
        $imagesInfos = $this->MapsRepository->getImagesInfos($idserie);
        $sequence = $this->gameRepository->createSequence($idserie,$imagesInfos);
        $game = $this->gameRepository->createGame($sequence,$idUser);
        $gameDTO = new GameDTO();
        return $gameDTO;
    }
}