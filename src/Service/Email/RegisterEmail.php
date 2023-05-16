<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class RegisterEmail extends EmailService
{
    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);

        return $email
            ->subject('Bienvenue !')
            ->htmlTemplate('emails/_registration.html.twig')
            ->context([
                'username' => $user->getUsername(),
            ])
        ;
    }
}
