<?php

namespace geoquizz_auth\core\services\auth;

use geoquizz_auth\core\dto\UserDTO;
use geoquizz_auth\core\dto\InputUserDTO;

interface AuthServiceInterface
{
    function verifyCredentials(InputUserDTO $input): UserDTO;
}