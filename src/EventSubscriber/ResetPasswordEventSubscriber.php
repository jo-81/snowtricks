<?php

namespace App\EventSubscriber;

use App\Entity\ResetPassword;
use Symfony\Contracts\EventDispatcher\Event;

class ResetPasswordEventSubscriber extends Event
{
    public const FORGET_PASSWORD = 'user.forget.password';

    public function __construct(private ResetPassword $resetPassword)
    {
    }

    public function getEntity(): ResetPassword
    {
        return $this->resetPassword;
    }
}
