<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserEventSubscriber extends Event
{
    public const REGISTRATION = 'user.registration';

    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
