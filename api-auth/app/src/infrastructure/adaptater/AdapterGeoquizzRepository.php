<?php

namespace geoquizz_auth\infrastructure\adaptater;


use geoquizz_auth\core\repositoryInterfaces\GeoquizzRepositoryInterface;

class AdapterGeoquizzRepository implements GeoquizzRepositoryInterface
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function createUser(string $idUser, ?string $pseudo): void
    {
        $this->client->post('/players', [
            'json' => ['id' => $idUser, 'pseudo' => $pseudo]
        ]);
    }

}