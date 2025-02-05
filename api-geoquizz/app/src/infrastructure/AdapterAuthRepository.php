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

        $response = $this->client->get('/x', [
            'headers' => ['Authorization' => 'Bearer '.$token]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return "";
    }

}