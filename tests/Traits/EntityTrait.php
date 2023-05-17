<?php

namespace App\Tests\Traits;

use App\Entity\ResetPassword;
use App\Entity\Trick;
use App\Entity\User;
use App\Repository\ResetPasswordRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;

trait EntityTrait
{
    /**
     * getUser.
     *
     * @param array<mixed> $criteria
     *
     * @return User
     */
    public function getUser(array $criteria): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        return $userRepository->findOneBy($criteria);
    }

    /**
     * getResetPassword.
     *
     * @param array<mixed> $criteria
     *
     * @return ResetPassword
     */
    public function getResetPassword(array $criteria): ?ResetPassword
    {
        /** @var ResetPasswordRepository $resetPasswordRepository */
        $resetPasswordRepository = static::getContainer()->get(ResetPasswordRepository::class);

        return $resetPasswordRepository->findOneBy($criteria);
    }

    /**
     * getTrick.
     *
     * @param array<mixed> $criteria
     *
     * @return Trick
     */
    public function getTrick(array $criteria): ?Trick
    {
        /** @var TrickRepository $trickRepository */
        $trickRepository = static::getContainer()->get(TrickRepository::class);

        return $trickRepository->findOneBy($criteria);
    }
}
