<?php

namespace geoquizz\core\services;

use geoquizz\core\domain\entity\Player;
use geoquizz\core\dto\InputPlayerDTO;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;
use geoquizz\infrastructure\PDOGameRepository;

class AuthorisationService implements AuthorisationServiceInterface
{
    private $authRepository;
    private GameRepositoryInterface $PDOGameRepository;

    public function __construct($authRepository, PDOGameRepository $PDOGameRep){
        $this->authRepository = $authRepository;
        $this->PDOGameRepository = $PDOGameRep;
    }

    public function playerID(string $token): string
    {
        return $this->authRepository->RecuperationIDPlayer($token);
    }

    public function creationPlayer(InputPlayerDTO $ipd): void
    {
        $player = new Player($ipd->pseudo);
        $player->setId($ipd->id);
        $this->PDOGameRepository->ajouterPlayer($player);
    }
}