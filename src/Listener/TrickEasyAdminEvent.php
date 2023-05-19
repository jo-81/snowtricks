<?php

namespace App\Listener;

use App\Entity\Trick;
use App\Entity\User;
use App\Service\Email\RegisterTrickEmail;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrickEasyAdminEvent implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RegisterTrickEmail $registerTrickEmail,
        private ContainerBagInterface $params,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['trick'],
            AfterEntityPersistedEvent::class => ['sendEmailToAdmin'],
        ];
    }

    public function trick(BeforeEntityPersistedEvent $event): void
    {
        $trick = $event->getEntityInstance();
        if ($trick instanceof Trick) {
            /** @var User $author */
            $author = $this->security->getUser();
            $trick->setAuthor($author);
            $isValided = $this->isValided($author->getRoles());

            $trick->setValided($isValided);
        }
    }

    public function sendEmailToAdmin(AfterEntityPersistedEvent $event): void
    {
        $trick = $event->getEntityInstance();

        if ($trick instanceof Trick) {
            /** @var User $user */
            $user = $this->security->getUser();

            /** @var string $email */
            $email = $this->params->get('app.admin_email');

            if (!$this->isValided($user->getRoles())) {
                $this->registerTrickEmail
                    ->getOptions($trick)
                    ->sendToEmail($email)
                    ->send($user)
                ;
            }
        }
    }

    /**
     * isValided.
     *
     * @param array<mixed> $roles
     */
    private function isValided(array $roles): bool
    {
        return in_array('ROLE_ADMIN', $roles) ? true : false;
    }
}
