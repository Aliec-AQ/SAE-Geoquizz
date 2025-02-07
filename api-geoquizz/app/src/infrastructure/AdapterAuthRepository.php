<?php

namespace geoquizz\infrastructure;

use Exception;
use geoquizz\core\domain\entity\Photo;
use geoquizz\core\repositoryInterfaces\AuthRepositoryInterface;
use geoquizz\core\repositoryInterfaces\MapsRepositoryException;
use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;

class AdapterAuthRepository implements AuthRepositoryInterface
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function RecuperationIDPlayer(string $token): string
    {
        $response = $this->client->get('/token/playerID', [
            'headers' => ['Authorization' => 'Bearer '.$token]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data["playerid"];
    }

    public function recuperationMailPlayer(string $idplayer): string {
        try {
            $response = $this->client->get('/users/mail', [
                'query' => ['playerid' => $idplayer]
            ]);
        }catch (Exception $e) {
            throw new MapsRepositoryException("Erreur lors de la récupération du mail");
        }

        $data = json_decode($response->getBody()->getContents(), true);
        return $data["mail"];
    }

}