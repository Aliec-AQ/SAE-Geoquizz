<?php

namespace core\services;

interface GameServiceInterface
{
    public function createGame($idserie) : GameDTO;
}