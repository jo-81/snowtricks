<?php

namespace App\Event;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\EventSubscriber\ResetPasswordEventSubscriber;
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
            UserEventSubscriber::RESET_PASSWORD => ['resetPassword'],
            ResetPasswordEventSubscriber::FORGET_PASSWORD => ['forgetPassword'],
        ];
    }

    public function registration(UserEventSubscriber $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $this->emailService->sendEmailRegistration($user);
    }

    public function resetPassword(UserEventSubscriber $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $this->emailService->sendEmailResetPassword($user);
    }

    public function forgetPassword(ResetPasswordEventSubscriber $event): void
    {
        /** @var ResetPassword $entity */
        $entity = $event->getEntity();

        $this->emailService->sendEmailForgetPassword($entity);
    }
}
