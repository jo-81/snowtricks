<?php

namespace App\Tests\Traits;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait UserLoginTrait
{
    /**
     * login.
     *
     * @param array<string, string> $identifiers
     */
    public function login(KernelBrowser $client, array $identifiers): ?User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        if ($userRepository instanceof UserRepository) {
            /** @var User $testUser */
            $testUser = $userRepository->findOneBy($identifiers);
            $client->loginUser($testUser);

            return $testUser;
        }

        return null;
    }
}
