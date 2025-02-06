<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\domain\entity\Photo;

interface MapsRepositoryInterface
{
    public function getImagesInfos(string $idSerie) : array;
    public function getThemesBySequences(array $sequences) : array;
    public function getPhotoByID(string $photoID) : Photo;
}