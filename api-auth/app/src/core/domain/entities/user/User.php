<?php

namespace geoquizz_auth\core\domain\entities\user;

use geoquizz_auth\core\domain\entities\Entity;
use geoquizz_auth\core\dto\UserDTO;

class User extends Entity
{
    protected string $email;
    protected int $role;
    protected string $password;
    protected string $pseudo;

    public function __construct(string $email, string $password, int $role, string $pseudo)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->pseudo = $pseudo;
    }

    public function toDTO(): UserDTO
    {
        return new UserDTO($this->ID, $this->email, $this->role, $this->pseudo);
    }
}