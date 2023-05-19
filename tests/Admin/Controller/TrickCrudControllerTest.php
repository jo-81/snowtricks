<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\DashboardController;
use App\Controller\Admin\TrickCrudController;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

class TrickCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;

    protected function getControllerFqcn(): string
    {
        return TrickCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    /**
     * testAccessTricksWhenUserIsNotLogged.
     */
    public function testAccessTricksWhenUserIsNotLogged(): void
    {
        $this->client->request('GET', $this->getCrudUrl('index'));

        static::assertResponseRedirects('/connexion');
    }

    /**
     * testAccessSingleTrickWhenUserIsNotLogged.
     *
     * @dataProvider dataProviderPageWhenUserNotLogged
     *
     * @return void
     */
    public function testAccessSingleTrickWhenUserIsNotLogged(string $page)
    {
        $this->client->request('GET', $this->getCrudUrl($page, 1));

        static::assertResponseRedirects('/connexion');
    }

    /**
     * testAccessSingleTrickWhenUserIsLoggedWithAdminRole.
     *
     * @dataProvider dataProviderPageWhenUserLoggedRoleAdmin
     *
     * @return void
     */
    public function testAccessSingleTrickWhenUserIsLoggedWithAdminRole(string $page, int $statusCode)
    {
        $this->login($this->client, ['id' => '1']);
        $this->client->request('GET', $this->getCrudUrl($page, 1));

        static::assertResponseStatusCodeSame($statusCode);
    }

    /**
     * dataProviderPageWhenUserLoggedRoleAdmin.
     *
     * @return array<mixed>
     */
    public function dataProviderPageWhenUserLoggedRoleAdmin(): array
    {
        return [
            ['detail', Response::HTTP_OK],
            ['edit', Response::HTTP_OK],
        ];
    }

    /**
     * dataProviderPageWhenUserNotLogged.
     *
     * @return array<mixed>
     */
    public function dataProviderPageWhenUserNotLogged(): array
    {
        return [
            ['detail'],
            ['edit'],
        ];
    }
}
