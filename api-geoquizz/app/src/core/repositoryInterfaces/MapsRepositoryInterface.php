<?php

namespace geoquizz\core\repositoryInterfaces;

interface MapsRepositoryInterface
{
    public function getImagesInfos(string $idSerie) : array;
    public function getThemesBySequences(array $sequences) : array;
}