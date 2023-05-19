<?php

namespace App\Service\Email;

use App\Controller\Admin\TrickCrudController;
use App\Entity\Trick;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class RegisterTrickEmail extends EmailService
{
    public function __construct(
        /** @phpstan-ignore-next-line */
        private MailerInterface $mailer,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
        parent::__construct($mailer);
    }

    public function builderEmail(User $user): TemplatedEmail
    {
        $email = parent::builderEmail($user);

        /** @var Trick $trick */
        $trick = $this->options;

        $url = $this->adminUrlGenerator
            ->setController(TrickCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($trick->getId())
            ->generateUrl();

        return $email
            ->subject('Nouveau contenu')
            ->htmlTemplate('emails/_register-trick-email.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'path' => $url,
            ])
        ;
    }
}
