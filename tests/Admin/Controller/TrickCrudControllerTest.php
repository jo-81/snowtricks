<?php

namespace App\Tests\Admin\Controller;

use App\Entity\Trick;
use App\Tests\Traits\EntityTrait;
use App\Tests\Traits\UserLoginTrait;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\TrickCrudController;
use Symfony\Component\HttpFoundation\Response;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;

class TrickCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;
    use EntityTrait;

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
            ['delete'],
        ];
    }

    public function testAccessRouteWithUserLoggedRoleUser(): void
    {
        // Appartient à l'auteur
        $user = $this->login($this->client, ['id' => '2']);

        /** @var Trick $trick */
        $trick = $this->getTrick(['author' => $user]);

        $this->client->request('GET', $this->getCrudUrl('edit', $trick->getId()));
        static::assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->client->request('GET', $this->getCrudUrl('delete', $trick->getId()));
        static::assertResponseRedirects();

        $this->client->request('GET', $this->getCrudUrl('detail', $trick->getId()));
        static::assertResponseStatusCodeSame(Response::HTTP_OK);

        // N'appartient pas à l'auteur
        $this->login($this->client, ['id' => '3']);
        $this->client->request('GET', $this->getCrudUrl('edit', $trick->getId()));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->request('GET', $this->getCrudUrl('delete', $trick->getId()));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->request('GET', $this->getCrudUrl('detail', $trick->getId()));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
