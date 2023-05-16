<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfilVoter extends Voter
{
    public const PROFILE = 'USER_PROFILE';
    public const EDIT = 'USER_EDIT';
    public const DELETE = 'USER_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::PROFILE, self::EDIT, self::DELETE])
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
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject) {
                    return true;
                }
                break;

            case self::EDIT:
                if ($user == $subject) {
                    return true;
                }
                break;

            case self::DELETE:
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    return true;
                }
                break;
        }

        return false;
    }
}
