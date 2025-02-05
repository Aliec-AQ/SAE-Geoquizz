<?php

namespace geoquizz\core\services;

use geoquizz\core\dto\PublicSequencesDTO;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\core\dto\GameDTO;
use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;


class GameService implements GameServiceInterface
{
    private GameRepositoryInterface $gameRepository;
    private MapsRepositoryInterface $mapsRepository;

    public function __construct(GameRepositoryInterface $gameRepository, MapsRepositoryInterface $mapsRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->mapsRepository = $mapsRepository;
    }

    public function createGame(string $idserie, string $idUser): GameDTO
    {
        $imagesInfos = $this->mapsRepository->getImagesInfos($idserie);
        $sequence = $this->gameRepository->createSequence($idserie,$imagesInfos);
        $game = $this->gameRepository->createGame($sequence,$idUser);
        $gameDTO = $game->toDTO();
        return $gameDTO;
    }

    public function getPublicSequences(): array
    {
        $sequences = $this->gameRepository->getPublicSequences();
        $sequencesHighScore = $this->gameRepository->getHighScore($sequences);
        $sequencesTheme = $this->mapsRepository->getThemesBySequences($sequences);

        $publicSequences = [];
        foreach ($sequences as $sequence) {
            $publicSequences[] = new PublicSequencesDTO($sequence->ID, $sequence->serie_id, $sequencesTheme[$sequence->ID], $sequencesHighScore[$sequence->ID]);
        }
        return $publicSequences;
    }


    public function changeSequenceStatus(string $idSequence): void
    {
        $this->gameRepository->changeSequenceStatus($idSequence);
    }

    public function finishGame(string $idGame, int $score): void
    {
        $this->gameRepository->finishGame($idGame);
    }

    public function historiqueGames(string $userId): array
    {
        return $this->gameRepository->historiqueGames($userId);
    }
}