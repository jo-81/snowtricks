<?php

namespace App\Security;

use App\Entity\User as AppUser;
use App\Repository\BlockedRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(private BlockedRepository $blockedRepository)
    {
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!empty($this->blockedRepository->findBy(['person' => $user]))) {
            throw new CustomUserMessageAccountStatusException('Votre compte est bloqué !');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!empty($this->blockedRepository->findBy(['person' => $user]))) {
            throw new CustomUserMessageAccountStatusException('Votre compte est bloqué !');
        }
    }
}