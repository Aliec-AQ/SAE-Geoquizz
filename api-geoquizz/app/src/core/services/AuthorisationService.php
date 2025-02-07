<?php

namespace geoquizz\core\services;

use Exception;
use geoquizz\core\domain\entity\Player;
use geoquizz\core\dto\InputPlayerDTO;
use geoquizz\core\repositoryInterfaces\AuthRepositoryInterface;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\infrastructure\PDOGameRepository;

class AuthorisationService implements AuthorisationServiceInterface
{
    private AuthRepositoryInterface $authRepository;
    private GameRepositoryInterface $PDOGameRepository;

    public function __construct($authRepository, PDOGameRepository $PDOGameRep){
        $this->authRepository = $authRepository;
        $this->PDOGameRepository = $PDOGameRep;
    }

    public function playerID(string $token): string
    {
        try {
            return $this->authRepository->RecuperationIDPlayer($token);
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de la récupération de l'id du joueur");
        }
    }

    public function playerEmail(string $idplayer): string{
        try {
            return $this->authRepository->recuperationMailPlayer($idplayer);
        }catch (Exception $e) {
            throw new \Exception("Erreur lors de la récupération du mail");
        }

    }

    public function creationPlayer(InputPlayerDTO $ipd): void
    {
        $player = new Player($ipd->pseudo);
        $player->setId($ipd->id);
        $this->PDOGameRepository->ajouterPlayer($player);
    }
}