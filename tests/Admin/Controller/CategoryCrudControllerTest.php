<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\DashboardController;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

class CategoryCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;

    protected function getControllerFqcn(): string
    {
        return CategoryCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    /**
     * testAccessCategoryWhenUserNotLogged.
     *
     * @dataProvider dataProviderPageCategory
     */
    public function testAccessCategoryWhenUserNotLogged(string $page): void
    {
        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseRedirects('/connexion');

        $this->client->request('GET', $this->getCrudUrl($page, '1'));
        static::assertResponseRedirects('/connexion');
    }

    /**
     * testAccessCategoryWhenUserLoggedWithRoleAdmin.
     *
     * @dataProvider dataProviderPageCategory
     */
    public function testAccessCategoryWhenUserLoggedWithRoleAdmin(string $page): void
    {
        $this->login($this->client, ['id' => '1']);

        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseIsSuccessful();

        $this->client->request('GET', $this->getCrudUrl($page, '1'));
        static::assertResponseIsSuccessful();
    }

    /**
     * testAccessCategoryWhenUserLoggedWithRoleUser.
     *
     * @dataProvider dataProviderPageCategory
     */
    public function testAccessCategoryWhenUserLoggedWithRoleUser(string $page): void
    {
        $this->login($this->client, ['id' => '2']);

        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->request('GET', $this->getCrudUrl($page, '1'));
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * dataProviderPageCategory.
     *
     * @return array<int, array<int, string>>
     */
    public function dataProviderPageCategory(): array
    {
        return [
            ['detail'],
            ['new'],
            ['edit'],
        ];
    }
}
