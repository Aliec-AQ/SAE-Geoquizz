<?php

namespace geoquizz_auth\core\services\user;

use geoquizz_auth\core\repositoryInterfaces\GeoquizzRepositoryInterface;
use PHPUnit\Exception;
use geoquizz_auth\core\dto\UserDTO;
use geoquizz_auth\core\domain\entities\user\User;
use geoquizz_auth\core\dto\InputUserDTO;
use geoquizz_auth\core\repositoryInterfaces\UserRepositoryInterface;
use geoquizz_auth\core\services\user\UserServiceException;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    private GeoquizzRepositoryInterface $geoquizzRepository;


    public function __construct(UserRepositoryInterface $userRepository, GeoquizzRepositoryInterface $geoquizzRepository)
    {
        $this->userRepository = $userRepository;
        $this->geoquizzRepository = $geoquizzRepository;
    }

    public function createUser(InputUserDTO $input): void
    {
        try {
            $user = $this->userRepository->findByEmail($input->email);
        } catch (Exception) {
            throw new UserServiceException('Erreur lors de la recherche de l\'utilisateur');
        }
        if ($user) {
            throw new UserServiceException('Utilisateur déjà existant');
        }
        try {
            $user = new User(
                $input->email,
                password_hash($input->password, PASSWORD_DEFAULT),
                0
            );

            $id = $this->userRepository->save($user);
            $this->geoquizzRepository->createUser($id, $input->pseudo);
        } catch (\Exception $e) {
            throw new UserServiceException('Erreur lors de la création de l\'utilisateur' . $e->getMessage());
        }

    }

    public function findUserById(string $ID): UserDTO
    {
        try {
            $user = $this->userRepository->findById($ID);
            if (!$user) {
                throw new UserServiceException('Utilisateur introuvable');
            }
            return $user->toDTO();
        } catch (\Exception $e) {
            throw new UserServiceException('Erreur lors de la recherche de l\'utilisateur');
        }
    }
}