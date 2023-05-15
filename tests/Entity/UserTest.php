<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\Traits\ValidatorTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use ValidatorTrait;

    public function getUser(): User
    {
        return (new User())
            ->setUsername('usertest')
            ->setEmail('usertest@domaine.fr')
            ->setPlainPassword('Azerty2000')
        ;
    }

    public function testUniqueUsername(): void
    {
        $user = $this->getUser();
        $user->setUsername('admin');

        $this->assertHasErrors($user, 1);
    }

    public function testUniqueEmail(): void
    {
        $user = $this->getUser();
        $user->setEmail('admin@domaine.fr');

        $this->assertHasErrors($user, 1);
    }

    public function testEmailConstraint(): void
    {
        $user = $this->getUser();
        $user->setEmail('admin.fr');

        $this->assertHasErrors($user, 1);
    }

    /**
     * testUsernameConstraints
     *      - username doit contenir un minimum de 5 caractères.
     */
    public function testUsernameConstraints(): void
    {
        $user = $this->getUser();
        $user->setUsername('ert');

        $this->assertHasErrors($user, 1);
    }

    /**
     * testPasswordConstraints
     * Test le mot de passe qui doit contenir
     *      - au moins une majuscule
     *      - 10 caractères au minimum
     *      - au moins un nombre.
     */
    public function testPasswordConstraints(): void
    {
        $user = $this->getUser();

        $user->setPlainPassword('azerty2000');
        $this->assertHasErrors($user, 1);

        $user->setPlainPassword('Azertyaaaa');
        $this->assertHasErrors($user, 1);

        $user->setPlainPassword('Azert1');
        $this->assertHasErrors($user, 1);
    }

    public function testGoodEntitiy(): void
    {
        $this->assertHasErrors($this->getUser(), 0);
    }
}
