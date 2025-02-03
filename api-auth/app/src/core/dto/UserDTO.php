<?php

namespace geoquizz_auth\core\dto;

class UserDTO extends DTO
{
    protected string $id;
    protected string $email;
    protected string $pseudo;
    protected int $role;
    protected ?string $accessToken;
    protected ?string $refreshToken;

    public function __construct(string $id, string $email, int $role, string $pseudo, ?string $accessToken = null, ?string $refreshToken = null) {
        $this->id = $id;
        $this->email = $email;
        $this->pseudo = $pseudo;
        $this->role = $role;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }
}