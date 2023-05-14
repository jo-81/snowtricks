<?php

namespace App\Event;

use App\Entity\User;
use App\EventSubscriber\UserEventSubscriber;
use App\Service\Email\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEvent implements EventSubscriberInterface
{
    public function __construct(private EmailService $emailService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEventSubscriber::REGISTRATION => ['registration'],
        ];
    }

    public function registration(UserEventSubscriber $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $this->emailService->sendEmailRegistration($user);
    }
}
