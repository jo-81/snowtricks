<?php

namespace App\Listener;

use App\Entity\User;
use App\Service\Email\EmailService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(private EmailService $emailService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityDeletedEvent::class => ['remove'],
        ];
    }

    public function remove(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof User) {
            $this->emailService->sendEmailRemoveUser($entity);
        }
    }
}
