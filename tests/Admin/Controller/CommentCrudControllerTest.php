<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\CommentCrudController;
use App\Controller\Admin\DashboardController;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CommentCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;

    protected function getControllerFqcn(): string
    {
        return CommentCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    public function testAccessRouteIndex(): void
    {
        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseRedirects('/connexion');

        $this->login($this->client, ['id' => '1']);
        $this->client->request('GET', $this->getCrudUrl('index'));
        static::assertResponseIsSuccessful();
    }
}
