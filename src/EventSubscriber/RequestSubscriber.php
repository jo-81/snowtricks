<?php

namespace App\EventSubscriber;

use App\Entity\Blocked;
use App\Repository\BlockedRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    public function __construct(private Security $security, private BlockedRepository $blockedRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();
        $blocked = $this->blockedRepository->findOneBy(['person' => $user]);

        if ($blocked instanceof Blocked) {
            $this->security->logout(false);
        }
    }
}
