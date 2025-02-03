<?php

namespace geoquizz_auth\core\repositoryInterfaces;

use geoquizz_auth\core\domain\entities\user\User;

interface UserRepositoryInterface
{
    function findByEmail(string $email):User | null;
    function save(User $auth): string;
    function findById(string $id):User;
}
