<?php

namespace App\Listener;

use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatableMessage;

class EasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(private RequestStack $request)
    {
    }

    /**
     * getSubscribedEvents.
     *
     * @return array<mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityUpdatedEvent::class => ['updated'],
            AfterEntityPersistedEvent::class => ['persisted'],
            AfterEntityDeletedEvent::class => ['deleted'],
        ];
    }

    public function updated(AfterEntityUpdatedEvent $event): void
    {
        $this->addMessage('update');
    }

    public function persisted(AfterEntityPersistedEvent $event): void
    {
        $this->addMessage('create');
    }

    public function deleted(AfterEntityDeletedEvent $event): void
    {
        $this->addMessage('delete');
    }

    private function addMessage(string $eventName): void
    {
        /** @var Session $session */
        $session = $this->request->getCurrentRequest()->getSession(); /* @phpstan-ignore-line */
        $session->getFlashBag()->add('success', new TranslatableMessage("content_admin.flash_message.$eventName", [], 'admin')); /* @phpstan-ignore-line */
    }
}
