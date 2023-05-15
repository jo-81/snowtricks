<?php

namespace App\Service\Email;

use App\Entity\Blocked;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendEmailRegistration(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from('snowtricks@domaine.fr')
            ->to($user->getEmail()) /* @phpstan-ignore-line */
            ->subject('Bienvenue !')
            ->htmlTemplate('emails/_registration.html.twig')
            ->context([
                'username' => $user->getUsername(),
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendEmailBlockedUser(Blocked $blocked): void
    {
        /** @var User $user */
        $user = $blocked->getPerson();

        $email = (new TemplatedEmail())
            ->from('snowtricks@domaine.fr')
            ->to($user->getEmail()) /* @phpstan-ignore-line */
            ->subject('Compte bloqué')
            ->htmlTemplate('emails/_blocked-account.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'blocked' => $blocked,
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendEmailUnblockedUser(Blocked $blocked): void
    {
        /** @var User $user */
        $user = $blocked->getPerson();

        $email = (new TemplatedEmail())
            ->from('snowtricks@domaine.fr')
            ->to($user->getEmail()) /* @phpstan-ignore-line */
            ->subject('Compte débloqué')
            ->htmlTemplate('emails/_unblocked-account.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'blocked' => $blocked,
            ])
        ;

        $this->mailer->send($email);
    }
}
