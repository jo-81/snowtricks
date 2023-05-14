<?php

namespace App\Service\Email;

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
}
