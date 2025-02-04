<?php

namespace geoquizz\infrastructure;

use geoquizz\core\domain\entity\Photo;
use geoquizz\core\repositoryInterfaces\MapsRepositoryInterface;

class AdapterMapsRepository implements MapsRepositoryInterface
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getImagesInfos($idSerie): array
    {
        $response = $this->client->get('/items/themes?fields=photos.photos_id.*&filter={"id":{"_eq":"'. $idSerie . '"}}');
        $data = json_decode($response->getBody()->getContents(), true);
        $randomData = array_rand($data, 10);

        $returnData = [];
        foreach ($randomData as $photo) {
            $p = new Photo($photo['nom'], $photo['image'], $photo['lat'], $photo['long']);
            $returnData[] = $p;
        }
        return $returnData;
    }
}