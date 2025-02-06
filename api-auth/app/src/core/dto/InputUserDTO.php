<?php

namespace geoquizz_auth\core\dto;
class InputUserDTO extends DTO
{
    protected string $email;
    protected string $password;
    protected ?string $pseudo;

    public function __construct(string $email, string $password, string $pseudo = null) {
        $this->email = $email;
        $this->password = $password;
        $this->pseudo = $pseudo;
    }

}