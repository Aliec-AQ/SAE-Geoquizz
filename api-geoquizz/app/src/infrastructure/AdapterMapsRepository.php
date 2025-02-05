<?php

namespace geoquizz\infrastructure;

use Exception;
use geoquizz\core\domain\entity\Photo;
use geoquizz\core\repositoryInterfaces\MapsRepositoryException;
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

    public function getPhotoByID(string $photoID):Photo{
        try{
            $response = $this->client->get('/items/photos?filter={"id":{"_eq":"'. $photoID . '"}}');
            $data = json_decode($response->getBody()->getContents(), true);
            return new Photo($data[0]['nom'], $data[0]['image'], $data[0]['lat'], $data[0]['long']);
        }catch (Exception $e){
            throw new MapsRepositoryException("Erreur lors de la récupération de la photo : ". $e->getMessage());
        }


    }


}