<?php

namespace geoquizz\core\repositoryInterfaces;

interface MapsRepositoryInterface
{
    public function getImagesInfos($idSequence) : array;
}