<?php

namespace geoquizz_auth\core\services\user;

use geoquizz_auth\core\dto\UserDTO;
use geoquizz_auth\core\dto\InputUserDTO;

interface UserServiceInterface
{
    public function findUserById(string $ID): UserDTO;
    public function createUser(InputUserDTO $input): void;
}