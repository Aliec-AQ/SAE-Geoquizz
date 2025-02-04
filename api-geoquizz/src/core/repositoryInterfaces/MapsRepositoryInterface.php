<?php

namespace core\repositoryInterfaces;

interface MapsRepositoryInterface
{
    public function getImagesInfos($idSequence) : array;
}