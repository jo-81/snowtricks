<?php

namespace App\Service\Password;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdatePasswordService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    /**
     * password
     * Permet d'éditer le mot de passe.
     */
    public function password(User $user): void
    {
        if (!is_null($user->getplainPassword())) {
            $passwordHashed = $this->hasher->hashPassword($user, $user->getPlainPassword()); /* @phpstan-ignore-line */
            $this->userRepository->upgradePassword($user, $passwordHashed);
        }
    }
}
