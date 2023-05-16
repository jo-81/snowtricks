<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function send(User $user): void
    {
        $this->mailer->send($this->builderEmail($user));
    }

    protected function builderEmail(User $user): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from('snowtricks@domaine.fr')
            ->to($user->getEmail()) /* @phpstan-ignore-line */
        ;
    }
}
