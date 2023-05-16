<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UnblockedEmail extends EmailService
{
    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);

        return $email
            ->subject('Compte débloqué')
            ->htmlTemplate('emails/_unblocked-account.html.twig')
            ->context([
                'username' => $user->getUsername(),
            ])
        ;
    }
}
