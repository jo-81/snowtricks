<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\DashboardController;
use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

class UserCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;

    protected function getControllerFqcn(): string
    {
        return UserCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    /**
     * testAccessAdminWhenUserIsNotLogged
     * Utilisateur non connecté voulant accéder à la page profil.
     */
    public function testAccessAdminWhenUserIsNotLogged(): void
    {
        $this->client->request('GET', $this->getCrudUrl('detail'));
        static::assertResponseRedirects('/connexion');
    }

    /**
     * testAccessAdminWhenUserIsLogged
     * Utilisateur connecté.
     */
    public function testAccessAdminWhenUserIsLogged(): void
    {
        $user = $this->login($this->client, ['username' => 'admin']);
        if ($user instanceof User) {
            $this->client->request('GET', $this->getCrudUrl('detail', $user->getId()));
        }
        static::assertResponseIsSuccessful();
    }

    /**
     * testAccessAdminWhenUserIsLoggedAndNotProfilPage
     * Utilisateur avec le rôle ROLE_ADMIN.
     */
    public function testAccessAdminWhenUserIsLoggedAndNotProfilPage(): void
    {
        $this->login($this->client, ['username' => 'admin']);
        $this->client->request('GET', $this->getCrudUrl('detail', 2));
        static::assertResponseIsSuccessful();
    }

    /**
     * testAccessAdminWhenUserIsLoggedAndNotProfilPageAndRoleNotAdmin
     * Utilisateur avec le rôle ROLE_USER.
     */
    public function testAccessAdminWhenUserIsLoggedAndNotProfilPageAndRoleNotAdmin(): void
    {
        $this->login($this->client, ['id' => '2']);
        /* Accès à son compte */
        $this->client->request('GET', $this->getCrudUrl('detail', 2));
        static::assertResponseIsSuccessful();

        /* Voulant accéder à un autre compte */
        $this->client->request('GET', $this->getCrudUrl('detail', 3));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * testAccessPageUserListWhenUserNotLogged
     * Utilisateur non connecté.
     */
    public function testAccessPageUserListWhenUserNotLogged(): void
    {
        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseRedirects('/connexion');
    }

    /**
     * testAccessPageUserListWhenUserLoggedWithRoleAdmin
     * Utilisateur avec le role ROLE_ADMIN.
     */
    public function testAccessPageUserListWhenUserLoggedWithRoleAdmin(): void
    {
        $this->login($this->client, ['id' => '1']);
        $this->client->request('GET', $this->getCrudUrl('index', 1));
        static::assertResponseIsSuccessful();
    }

    /**
     * testAccessPageUserListWhenUserLoggedWithRoleAdmin
     * Utilisateur avec le role ROLE_USER.
     */
    public function testAccessPageUserListWhenUserLoggedWithRoleUser(): void
    {
        $this->login($this->client, ['id' => '2']);
        $this->client->request('GET', $this->getCrudUrl('index', 2));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * testAccessEditProfil
     * Utilisateur connecté voulant modifier ses informations.
     */
    public function testAccessEditProfil(): void
    {
        $this->login($this->client, ['id' => '2']);
        $this->client->request('GET', $this->getCrudUrl('edit', 2));
        static::assertResponseIsSuccessful();

        $this->client->request('GET', $this->getCrudUrl('edit', 3));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
