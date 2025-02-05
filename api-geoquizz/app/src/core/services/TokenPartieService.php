<?php

namespace geoquizz\core\services;

use Exception;
use geoquizz\core\repositoryInterfaces\GameRepositoryInterface;

class TokenPartieService implements TokenPartieServiceInterface
{

    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository){
        $this->gameRepository = $gameRepository;
    }

    public function verifyPartie(string $idGame){
        try{
            $game = $this->gameRepository->findGameByIdForAuthorisation($idGame);
            if($game->status != false){
                throw new TokenServiceException("la partie est deja terminee");
            }
        }catch (Exception $e){
            throw new TokenServiceException($e->getMessage());
        }



    }
}