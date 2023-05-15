<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use Twig\Extension\RuntimeExtensionInterface;

class AvatarExtensionRuntime implements RuntimeExtensionInterface
{
    public function doAvatar(User $user): string
    {
        $avatar = null != $user->getAvatar() ? '/images/avatar/'.$user->getAvatar()->getPath() : '/images/avatar.png';

        return "<img width='50' class='img-fluid shadow rounded-pill' src='$avatar' alt=''>";
    }
}
