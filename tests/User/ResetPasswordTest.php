<?php

namespace App\Tests\User;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Tests\Traits\EntityTrait;
use App\Tests\Traits\UserLoginTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use UserLoginTrait;
    use EntityTrait;

    public function testRouteExist(): void
    {
        $client = static::createClient();
        $client->request('GET', '/forget-password');

        $this->assertResponseIsSuccessful();
    }

    public function testRedirectionIfUserLogged(): void
    {
        $client = static::createClient();
        $this->login($client, ['id' => '1']);
        $client->request('GET', '/forget-password');

        $this->assertResponseRedirects('/');
    }

    /**
     * testForgetPasswordWithGoodEmail
     * L'ajout d'une demande de modification de mot de passe.
     */
    public function testForgetPasswordWithGoodEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/forget-password');

        /** @var User $user */
        $user = $this->getUser(['id' => 2]);

        $form = $crawler->selectButton('Valider')->form();
        $form['email'] = $user->getEmail(); /* @phpstan-ignore-line */
        $client->submit($form);

        $this->assertResponseRedirects('/');
        $this->assertInstanceOf(ResetPassword::class, $this->getResetPassword(['person' => $user]));
        $this->assertQueuedEmailCount(1);
    }

    /**
     * testRouteResetPasswordWithoutToken
     * Pour réinitialiser son mot de passe sans avoir fait la demande.
     */
    public function testRouteResetPasswordWithoutToken(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reset-password');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * testRouteResetPasswordWithToken
     * Pour réinitialiser son mot de passe avec un bon token.
     */
    public function testRouteResetPasswordWithToken(): void
    {
        $client = static::createClient();
        $resetPassword = $this->getResetPassword(['id' => '1']);
        $client->request('GET', '/reset-password/'.$resetPassword->getToken()); /* @phpstan-ignore-line */

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * testRouteResetPasswordWithBadToken.
     */
    public function testRouteResetPasswordWithBadToken(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reset-password/bad-token');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
