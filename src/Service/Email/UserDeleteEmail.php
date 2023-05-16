<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UserDeleteEmail extends EmailService
{
    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);

        return $email
            ->subject('Suppression de votre compte')
            ->htmlTemplate('emails/_remove_account.html.twig')
            ->context([
                'username' => $user->getUsername(),
            ])
        ;
    }
}
