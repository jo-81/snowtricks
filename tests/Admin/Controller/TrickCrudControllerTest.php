<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\DashboardController;
use App\Controller\Admin\TrickCrudController;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

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
     *
     * @dataProvider dataProviderPageWhenUserNotLogged
     */
    public function testAccessTricksWhenUserIsNotLogged(string $page): void
    {
        $this->client->request('GET', $this->getCrudUrl($page));
        static::assertResponseRedirects('/connexion');
    }

    /**
     * dataProviderPageWhenUserNotLogged.
     *
     * @return array<int, array<int, string>>
     */
    public function dataProviderPageWhenUserNotLogged(): array
    {
        return [
            ['index'],
        ];
    }
}
