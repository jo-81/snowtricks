<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(private RequestStack $request)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityUpdatedEvent::class => ['messageFlash'],
        ];
    }

    public function messageFlash(AfterEntityUpdatedEvent $event)
    {
        /** @var Session $session */
        $session = $this->request->getCurrentRequest()?->getSession();
        $session?->getFlashBag()->add('success', new TranslatableMessage('content_admin.flash_message.update', [], 'admin'));
    }
}