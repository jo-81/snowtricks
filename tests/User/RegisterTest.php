<?php

namespace App\Tests\User;

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
}
