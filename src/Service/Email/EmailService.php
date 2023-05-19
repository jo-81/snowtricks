<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    protected mixed $options = null;
    protected ?string $emailTo = null;

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
        /** @var string $emailTo */
        $emailTo = null == $this->emailTo ? $user->getEmail() : $this->emailTo;

        return (new TemplatedEmail())
            ->from('snowtricks@domaine.fr')
            ->to($emailTo)
        ;
    }

    public function getOptions(mixed $options = null): self
    {
        $this->options = $options;

        return $this;
    }

    public function sendToEmail(string $email): self
    {
        $this->emailTo = $email;

        return $this;
    }
}
