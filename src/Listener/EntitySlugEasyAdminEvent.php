<?php

namespace App\Listener;

use App\Entity\EntitySlugInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EntitySlugEasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(private SluggerInterface $sluggerInterface)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => ['generateSlug'],
            BeforeEntityPersistedEvent::class => ['generateSlug'],
        ];
    }

    public function generateSlug(AbstractLifecycleEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof EntitySlugInterface) {
            $method = 'get'.ucfirst($entity->targetField());
            $entity->setSlug($this->sluggerInterface->slug($entity->$method(), '-', 'fr-FR')->lower());
        }
    }
}
