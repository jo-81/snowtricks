<?php

namespace App\Service\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHashService
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function passwordHash(User $user): User
    {
        if (!is_null($user->getPlainPassword())) {
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
        }

        return $user;
    }
}
