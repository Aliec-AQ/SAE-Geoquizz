<?php
namespace geoquizz\application\providers\tokenPartie;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTManager
{
    private $secretKey;
    private $algorithm;

    public function __construct($secretKey, $algorithm = 'HS256')
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
    }

    public function createToken(array $payload): string{
        $payload['exp'] = time() + 3600;
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function decodeToken(string $token): array{
        return (array) JWT::decode($token,new Key( $this->secretKey, $this->algorithm));
    }

}