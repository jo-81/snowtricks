<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const SHOW = 'COMMENT_SHOW';
    public const EDIT = 'COMMENT_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::SHOW, self::EDIT]) && $subject instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::SHOW:
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject->getAuthor()) { /* @phpstan-ignore-line */
                    return true;
                }
                break;

            case self::EDIT:
                if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject->getAuthor()) { /* @phpstan-ignore-line */
                    return true;
                }
                break;
        }

        return false;
    }
}
