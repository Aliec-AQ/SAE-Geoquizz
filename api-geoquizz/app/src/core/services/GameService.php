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
        $imagesInfos = $this->mapsRepository->getImagesRandoms($idserie);
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
        $this->gameRepository->finishGame($idGame, $score);
    }

    public function historiqueGames(string $userId): array
    {
        $historique =  $this->gameRepository->historiqueGames($userId);
        $games = [];
        foreach ($historique as $game) {
            $games[] = $this->gameRepository->gameById($game['id'])->toDTO();
        }
        return $games;
    }

    public function gameById(string $id): GameDTO
    {
        $game = $this->gameRepository->gameById($id);
        $images = $this->gameRepository->getIDPhotosByIDSequence($game->sequence->ID);
        $photos = [];
        foreach ($images as $id) {
            $photos[] = $this->mapsRepository->getPhotoByID($id);
        }
        $game->sequence->setPhotos($photos);
        return $game->toDTO();
    }

    public function replaySequence(string $idSequence, ?string $idUser): GameDTO
    {
        $sequence = $this->gameRepository->getSequenceById($idSequence);
        $imagesIds = $this->gameRepository->getIDPhotosByIDSequence($idSequence);
        $images = [];
        foreach ($imagesIds as $id) {
            $images[] = $this->mapsRepository->getPhotoByID($id);
        }
        $sequence->setPhotos($images);
        $game = $this->gameRepository->createGame($sequence, $idUser);
        return $game->toDTO();
    }

    public function getHighscore(string $idSerie, $idUser): int
    {
        return $this->gameRepository->getHighscoreForUserBySerie($idSerie, $idUser);
    }
}