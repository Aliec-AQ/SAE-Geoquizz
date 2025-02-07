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
        try {
            $imagesInfos = $this->mapsRepository->getImagesRandoms($idserie);
            $sequence = $this->gameRepository->createSequence($idserie,$imagesInfos);
            $game = $this->gameRepository->createGame($sequence,$idUser);
            $gameDTO = $game->toDTO();
            return $gameDTO;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function getPublicSequences(): array
    {
        try {
            $sequences = $this->gameRepository->getPublicSequences();
            $sequencesHighScore = $this->gameRepository->getHighScore($sequences);
            $sequencesTheme = $this->mapsRepository->getThemesBySequences($sequences);

            $publicSequences = [];
            foreach ($sequences as $sequence) {
                $publicSequences[] = new PublicSequencesDTO($sequence->ID, $sequence->serie_id, $sequencesTheme[$sequence->ID], $sequencesHighScore[$sequence->ID]);
            }
            return $publicSequences;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }


    public function changeSequenceStatus(string $idSequence): void
    {
        try {

            $this->gameRepository->changeSequenceStatus($idSequence);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function finishGame(string $idGame, int $score): void
    {
        try {
            $this->gameRepository->finishGame($idGame, $score);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function historiqueGames(string $userId): array
    {
        try {
            $historique =  $this->gameRepository->historiqueGames($userId);
            $games = [];
            foreach ($historique as $game) {
                $games[] = $this->gameRepository->gameById($game['id'])->toDTO();
            }
            return $games;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function gameById(string $id): GameDTO
    {
        try {
            $game = $this->gameRepository->gameById($id);
            $images = $this->gameRepository->getIDPhotosByIDSequence($game->sequence->ID);
            $photos = [];
            foreach ($images as $id) {
                $photos[] = $this->mapsRepository->getPhotoByID($id);
            }
            $game->sequence->setPhotos($photos);
            return $game->toDTO();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function replaySequence(string $idSequence, ?string $idUser): GameDTO
    {
        try {
            $sequence = $this->gameRepository->getSequenceById($idSequence);
            $imagesIds = $this->gameRepository->getIDPhotosByIDSequence($idSequence);
            $images = [];
            foreach ($imagesIds as $id) {
                $images[] = $this->mapsRepository->getPhotoByID($id);
            }
            $sequence->setPhotos($images);
            $game = $this->gameRepository->createGame($sequence, $idUser);
            return $game->toDTO();
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function getHighscore(string $idSerie, $idUser): int
    {
        try {
            return $this->gameRepository->getHighscoreForUserBySerie($idSerie, $idUser);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}