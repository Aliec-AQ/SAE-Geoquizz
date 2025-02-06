<?php

namespace geoquizz_auth\core\repositoryInterfaces;

interface GeoquizzRepositoryInterface
{
    public function createUser(string $idUser, ?string $pseudo): void;

}