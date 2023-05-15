<?php

namespace App\Listener;

use App\Entity\Blocked;
use App\Service\Email\EmailService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BlockedEasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(private EmailService $emailService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['persisted'],
            AfterEntityDeletedEvent::class => ['deleted'],
        ];
    }

    public function persisted(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Blocked) {
            $this->emailService->sendEmailBlockedUser($entity);
        }
    }

    public function deleted(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Blocked) {
            $this->emailService->sendEmailUnblockedUser($entity);
        }
    }
}
