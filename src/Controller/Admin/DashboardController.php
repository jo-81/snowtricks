<?php

namespace App\Controller\Admin;

use App\Entity\Blocked;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\CommentSignaled;
use App\Entity\ResetPassword;
use App\Entity\Trick;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Snowtricks')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        /** @var User $user */
        $user = $this->getUser();

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home')->setPermission('ROLE_ADMIN');

        yield MenuItem::linkToUrl('Retour vers le site', 'fa fa-reply-all', '/');

        /* Utilisateurs */
        yield MenuItem::section('Utilisateurs')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Liste des utilisateurs', 'fa fa-users', User::class)
            ->setAction('index')
            ->setPermission('ROLE_ADMIN');

        yield MenuItem::linkToCrud('Utilisateurs bloqués', 'fa fa-lock', Blocked::class)
            ->setAction('index')
            ->setPermission('ROLE_ADMIN');

        yield MenuItem::linkToCrud('Demande de mot de passe', 'fa fa-at', ResetPassword::class)
            ->setAction('index')
            ->setPermission('ROLE_ADMIN');
        /* Utilisateurs */

        /* Figures */
        $titleIndexTrick = in_array('ROLE_ADMIN', $user->getRoles()) ? 'Liste des figures' : 'Mes rédactions';
        yield MenuItem::section('Figures');
        yield MenuItem::linkToCrud($titleIndexTrick, 'fa fa-person-snowboarding', Trick::class)
            ->setAction('index')
        ;
        /* Figures */

        /* Catégories */
        yield MenuItem::section('Catégories')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Liste des catégories', 'fa fa-tags', Category::class)
            ->setAction('index')
            ->setPermission('ROLE_ADMIN');
        /* Catégories */

        /* Commentaires */
        $titleIndexComment = in_array('ROLE_ADMIN', $user->getRoles()) ? 'Liste des commentaires' : 'Mes commentaires';
        yield MenuItem::section('Commentaires');
        yield MenuItem::linkToCrud($titleIndexComment, 'fa fa-comments', Comment::class)
            ->setAction('index')
        ;
        yield MenuItem::linkToCrud('Commentaires signalés', 'fa fa-triangle-exclamation', CommentSignaled::class)
            ->setAction('index')
            ->setPermission('ROLE_ADMIN')
        ;
        /* Commentaires */

        yield MenuItem::section();

        yield MenuItem::linkToCrud('Mon profil', 'fa fa-id-card', User::class)
            ->setAction('detail')
            ->setEntityId($user->getId());
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setPaginatorPageSize(10)
            ->setDateTimeFormat('full', 'short')
            ->setTimezone('Europe/Paris')
        ;
    }
}
