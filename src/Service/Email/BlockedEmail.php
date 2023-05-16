<?php

namespace App\Service\Email;

use App\Entity\User;
use App\Repository\BlockedRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class BlockedEmail extends EmailService
{
    public function __construct(
        /** @phpstan-ignore-next-line */
        private MailerInterface $mailer,
        private BlockedRepository $blockedRepository
    ) {
        parent::__construct($mailer);
    }

    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);
        $blocked = $this->blockedRepository->findOneBy(['person' => $user]);

        return $email
            ->subject('Compte bloqué')
            ->htmlTemplate('emails/_blocked-account.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'blocked' => $blocked,
            ])
        ;
    }
}
