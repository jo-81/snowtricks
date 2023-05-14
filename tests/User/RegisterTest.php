<?php

namespace App\Tests\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Traits\UserLoginTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use UserLoginTrait;

    public function testRouteExist(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $this->assertResponseIsSuccessful();
    }

    public function testRedirectionRouteWhenUserLogged(): void
    {
        $client = static::createClient();
        $this->login($client, ['username' => 'admin']);
        $client->request('GET', '/inscription');

        $this->assertResponseRedirects('/');
    }

    public function testRegisterUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $form = $crawler->selectButton('Inscription')->form();
        $form['register[username]'] = 'username';
        $form['register[email]'] = 'username@domaine.fr';
        $form['register[plainPassword]'] = 'Azerty2000';

        $client->submit($form);

        $this->assertResponseRedirects('/');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $newUser = $userRepository->findOneBy(['username' => 'username']); /* @phpstan-ignore-line */

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals($newUser->getUsername(), 'username');

        $this->assertQueuedEmailCount(1);
    }
}
