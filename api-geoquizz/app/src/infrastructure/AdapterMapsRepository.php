<?php

namespace geoquizz\infrastructure;

use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;

class AdapterMapsRepository implements MapsRepositoryInterface
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getImagesInfos($idSequence): array
    {
        $response = $this->client->get("");
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }
}