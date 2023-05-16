<?php

namespace App\Service\Email;

use App\Entity\User;
use App\Repository\ResetPasswordRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgetPasswordEmail extends EmailService
{
    public function __construct(
        /** @phpstan-ignore-next-line */
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator,
        private ResetPasswordRepository $resetPasswordRepository
    ) {
        parent::__construct($mailer);
    }

    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);
        $resetPassword = $this->resetPasswordRepository->findOneBy(['person' => $user]);

        return $email
            ->subject('Mot de passe oublié')
            ->htmlTemplate('emails/_forget_password.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'resetPassword' => $resetPassword,
                /* @phpstan-ignore-next-line */
                'path' => $this->urlGenerator->generate('user.reset.password', ['token' => $resetPassword->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            ])
        ;
    }
}
