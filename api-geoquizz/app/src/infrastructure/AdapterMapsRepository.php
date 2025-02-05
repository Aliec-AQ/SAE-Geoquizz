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
        $data = json_decode($response->getBody(), true);
        $randomData = array_rand($data["data"][0]["photos"], 10);
        $returnData = [];
        foreach ($randomData as $p) {
            $photo = $data["data"][0]["photos"][$p]["photos_id"];
            $p = new Photo($photo['nom'], $photo['image'], $photo['lat'], $photo['long']);
            $p->setId($photo['id']);
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


    public function getThemesBySequences(array $sequences): array
    {
        $themes = [];
        foreach ($sequences as $sequence) {
            $response = $this->client->get('/items/themes?fields=nom&filter={"id":{"_eq":"'. $sequence->serie_id . '"}}');
            $data = json_decode($response->getBody()->getContents(), true);
            $themes[$sequence->ID] = $data[0]['nom'];
        }
        return $themes;
    }
}