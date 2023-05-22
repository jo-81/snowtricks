<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const SHOW = 'COMMENT_SHOW';
    public const EDIT = 'COMMENT_EDIT';
    public const DELETE = 'COMMENT_DELETE';
    public const ACCESS = 'COMMENT_ACCESS';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::SHOW, self::EDIT, self::DELETE, self::ACCESS]) && $subject instanceof \App\Entity\Comment;
    }

    /**
     * voteOnAttribute.
     *
     * @param Comment $subject
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
                if ($this->isAccess($user, $subject)) {
                    return $this->isAccess($user, $subject);
                }
                break;

            case self::EDIT:
                if ($this->isAccess($user, $subject)) {
                    return $this->isAccess($user, $subject);
                }
                break;

            case self::DELETE:
                if ($this->isAccess($user, $subject)) {
                    return $this->isAccess($user, $subject);
                }
                break;

            case self::ACCESS:
                if ($this->isAccess($user, $subject)) {
                    return $this->isAccess($user, $subject);
                }
                break;
        }

        return false;
    }

    /**
     * isAccess.
     */
    private function isAccess(User $user, Comment $subject): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles()) || $user === $subject->getAuthor()) {
            return true;
        }

        return false;
    }
}
