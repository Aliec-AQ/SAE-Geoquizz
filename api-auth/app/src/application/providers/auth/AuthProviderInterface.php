<?php
namespace geoquizz_auth\application\providers\auth;

use geoquizz_auth\core\dto\UserDTO;
use geoquizz_auth\core\dto\InputUserDTO;

interface AuthProviderInterface
{
    public function signIn(InputUserDTO $credentials): UserDTO;

    public function getSignIn(string $token): UserDTO;
}