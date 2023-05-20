<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\CommentCrudController;
use App\Controller\Admin\DashboardController;
use App\Tests\Traits\EntityTrait;
use App\Tests\Traits\UserLoginTrait;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

class CommentCrudControllerTest extends AbstractCrudTestCase
{
    use UserLoginTrait;
    use ReloadDatabaseTrait;
    use EntityTrait;

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

    public function testAccessRouteDetail(): void
    {
        $this->client->request('GET', $this->getCrudUrl('detail', 1));
        static::assertResponseRedirects('/connexion');

        // Connecté en tant qu'admin
        $user = $this->login($this->client, ['id' => '1']);

        $comment = $this->getComment(['author' => $user]);
        if (!is_null($comment)) {
            $this->client->request('GET', $this->getCrudUrl('detail', $comment->getId()));
            static::assertResponseIsSuccessful();
        }
    }

    public function testAccessRouteDetailWithUserLoggedUser(): void
    {
        $user = $this->login($this->client, ['id' => '2']);
        $ownComment = $this->getComment(['author' => $user]);
        $otherComment = $this->getComment(['author' => $this->getUser(['id' => 3])]);

        if (!is_null($ownComment)) {
            // Appartenant à l'utilisateur connecté
            $this->client->request('GET', $this->getCrudUrl('detail', $ownComment->getId()));
            static::assertResponseIsSuccessful();

            $this->client->request('GET', $this->getCrudUrl('delete', $ownComment->getId()));
            static::assertResponseRedirects();

            $this->client->request('GET', $this->getCrudUrl('edit', $ownComment->getId()));
            static::assertResponseIsSuccessful();
        }

        if (!is_null($otherComment)) {
            // N'appartenant pas à l'utilisateur connecté
            $this->client->request('GET', $this->getCrudUrl('detail', $otherComment->getId()));
            static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

            $this->client->request('GET', $this->getCrudUrl('delete', $otherComment->getId()));
            static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

            $this->client->request('GET', $this->getCrudUrl('edit', $otherComment->getId()));
            static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }
}
