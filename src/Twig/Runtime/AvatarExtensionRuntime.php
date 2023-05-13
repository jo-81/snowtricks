<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use Twig\Extension\RuntimeExtensionInterface;

class AvatarExtensionRuntime implements RuntimeExtensionInterface
{
    public function doAvatar(User $user): string
    {
        $avatar = null != $user->getAvatar() ? $user->getAvatar()->getPath() : '/images/avatar/avatar.png';

        return "<img width='50' class='img-fluid rounded-pill' src='$avatar' alt=''>";
    }
}
