<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ResetPasswordEmail extends EmailService
{
    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);

        return $email
            ->subject('Ré initialisation de votre mot de passe')
            ->htmlTemplate('emails/_reset_password.html.twig')
            ->context([
                'username' => $user->getUsername(),
            ])
        ;
    }
}
