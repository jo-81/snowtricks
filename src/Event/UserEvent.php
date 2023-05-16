<?php

namespace App\Event;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\EventSubscriber\ResetPasswordEventSubscriber;
use App\EventSubscriber\UserEventSubscriber;
use App\Service\Email\ForgetPasswordEmail;
use App\Service\Email\RegisterEmail;
use App\Service\Email\ResetPasswordEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEvent implements EventSubscriberInterface
{
    public function __construct(
        private ForgetPasswordEmail $forgetPasswordEmail,
        private RegisterEmail $registerEmail,
        private ResetPasswordEmail $resetPasswordEmail
    ) {
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

        $this->registerEmail->send($user);
    }

    public function resetPassword(UserEventSubscriber $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $this->resetPasswordEmail->send($user);
    }

    public function forgetPassword(ResetPasswordEventSubscriber $event): void
    {
        /** @var ResetPassword $entity */
        $entity = $event->getEntity();

        /** @var User $user */
        $user = $entity->getPerson();
        $this->forgetPasswordEmail->send($user);
    }
}
