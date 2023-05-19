<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const INDEX_USER = 'COMMENT_INDEX_USER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::INDEX_USER]) && $subject instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::INDEX_USER:
                break;
        }

        return false;
    }
}
