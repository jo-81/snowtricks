<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfilVoter extends Voter
{
    public const PROFILE = 'USER_PROFILE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::PROFILE])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::PROFILE:
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $subject) {
                    return true;
                }
                break;
        }

        return false;
    }
}
