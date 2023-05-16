<?php

namespace App\Listener;

use App\Entity\Blocked;
use App\Entity\User;
use App\Service\Email\BlockedEmail;
use App\Service\Email\UnblockedEmail;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BlockedEasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(
        private BlockedEmail $blockedEmail,
        private UnblockedEmail $unblockedEmail,
    ) {
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
            /** @var User $user */
            $user = $entity->getPerson();
            $this->blockedEmail->send($user);
        }
    }

    public function deleted(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Blocked) {
            /** @var User $user */
            $user = $entity->getPerson();
            $this->unblockedEmail->send($user);
        }
    }
}
