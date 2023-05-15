<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\BlockedCrudController;
use App\Controller\Admin\DashboardController;
use App\Entity\User;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

class BlockedCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;

    protected function getControllerFqcn(): string
    {
        return BlockedCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    /**
     * testAccessAdminWhenUserIsNotLogged
     * Utilisateur non connecté voulant accéder à la liste des utilisateurs bloqués.
     */
    public function testAccessAdminWhenUserIsNotLogged(): void
    {
        $this->client->request('GET', $this->getCrudUrl('index'));
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
            $this->client->request('GET', $this->getCrudUrl('index'));
        }
        static::assertResponseIsSuccessful();
    }

    /**
     * testAccessAdminWhenUserIsLoggedAndNotProfilPage
     * Utilisateur avec le rôle ROLE_ADMIN.
     */
    public function testAccessAdminWhenUserIsLoggedAndNotProfilPage(): void
    {
        $this->login($this->client, ['id' => '2']);
        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
