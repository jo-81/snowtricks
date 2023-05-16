<?php

namespace App\Controller\Admin;

use App\Entity\ResetPassword;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ResetPasswordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResetPassword::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(CRUD::PAGE_INDEX, 'Demande de ré initialisation de mot de passe')
            ->setPageTitle(CRUD::PAGE_DETAIL, 'Demande')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('createdAt', 'Date de la demande')->hideOnForm(),
            DateTimeField::new('expiredAt', 'Dernière d\'expiration')->hideOnForm(),
            AssociationField::new('person', 'Utilisateur')->hideOnForm(),
        ];
    }
}
