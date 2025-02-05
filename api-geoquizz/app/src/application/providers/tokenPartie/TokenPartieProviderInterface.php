<?php
namespace geoquizz\application\providers\tokenPartie;


interface TokenPartieProviderInterface{
    public function getTokenPartie(string $token): string;

    public function createTokenPartie(string $idPartie): string;
}