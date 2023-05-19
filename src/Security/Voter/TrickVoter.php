<?php

namespace App\Security\Voter;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickVoter extends Voter
{
    public const SHOW = 'TRICK_SHOW';
    public const EDIT = 'TRICK_EDIT';
    public const DELETE = 'TRICK_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::SHOW, self::EDIT, self::DELETE]) && $subject instanceof \App\Entity\Trick;
    }

    /**
     * voteOnAttribute.
     *
     * @param Trick $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::SHOW:
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject->getAuthor()) {
                    return true;
                }
                break;

            case self::EDIT:
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject->getAuthor()) {
                    return true;
                }
                break;

            case self::DELETE:
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject->getAuthor()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
