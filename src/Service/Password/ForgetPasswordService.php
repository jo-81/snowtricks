<?php

namespace App\Service\Password;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ForgetPasswordService
{
    public function __construct(
        private UserRepository $userRepository,
        private ResetPasswordRepository $resetPasswordRepository,
        private TokenGeneratorInterface $tokenGenerator,
    ) {
    }

    /**
     * existUserByEmail
     * Si un utilisateur existe selon une adresse email.
     *
     * @return User
     */
    public function existUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * get
     * Retourne une demande d'un utilisateur.
     */
    public function get(User $user): ?ResetPassword
    {
        return $this->resetPasswordRepository->findOneBy(['person' => $user]);
    }

    /**
     * isExpired
     * Si la demande a expirée.
     */
    public function isExpired(ResetPassword $resetPassword): bool
    {
        $now = new \DateTimeImmutable();

        return $now > $resetPassword->getExpiredAt();
    }

    /**
     * persist.
     */
    public function persist(User $user): ResetPassword
    {
        $resetPassword = (new ResetPassword())
            ->setPerson($user)
            ->setToken($this->tokenGenerator->generateToken())
        ;
        $this->resetPasswordRepository->save($resetPassword, true);

        return $resetPassword;
    }

    /**
     * update.
     */
    public function update(ResetPassword $resetPassword): ResetPassword
    {
        $date = new \DateTimeImmutable();
        $expiredDate = $date->add(new \DateInterval('P1D'));

        $resetPassword->setCreatedAt($date);
        $resetPassword->setExpiredAt($expiredDate);

        $this->resetPasswordRepository->save($resetPassword, true);

        return $resetPassword;
    }
}
