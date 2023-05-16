<?php

namespace App\Listener;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdatePasswordAdminEvent implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => ['updatePassword'],
        ];
    }

    public function updatePassword(BeforeEntityUpdatedEvent $event): void
    {
        $user = $event->getEntityInstance();
        if (!is_null($user->getplainPassword())) {
            $passwordHashed = $this->hasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($passwordHashed);
        }
    }
}
