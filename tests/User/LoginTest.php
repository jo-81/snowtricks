<?php

namespace App\Tests\User;

use App\Tests\Traits\UserLoginTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use UserLoginTrait;

    /**
     * testRouteLoginExist.
     *
     * @return void
     */
    public function testRouteLoginExist()
    {
        $client = static::createClient();
        $client->request('GET', '/connexion');

        $this->assertResponseIsSuccessful();
    }

    public function testRedirectionRouteLoginWhenUserLogged(): void
    {
        $client = static::createClient();
        $this->login($client, ['username' => 'admin']);
        $client->request('GET', '/connexion');

        $this->assertResponseRedirects('/');
    }

    /**
     * testLoginWithGoodCredentials.
     */
    public function testLoginWithGoodCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['username'] = 'admin';
        $form['password'] = '0000';

        $client->submit($form);

        $this->assertResponseRedirects('/');
    }

    /**
     * testLoginWithBadCredentials.
     *
     * @return void
     */
    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['username'] = 'admin';
        $form['password'] = '0';

        $client->submit($form);

        $this->assertResponseRedirects('/connexion');
    }
}
