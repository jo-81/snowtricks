<?php

namespace App\Controller\Admin;

use App\Entity\Blocked;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class BlockedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blocked::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(CRUD::PAGE_INDEX, 'Utilisateurs bloqués')
            ->setPageTitle(CRUD::PAGE_DETAIL, 'Consulter le compte bloqué')
            ->setPageTitle(CRUD::PAGE_EDIT, 'Modifier le compte bloqué')
            ->setPageTitle(CRUD::PAGE_NEW, 'Bloquer un utilisateur')
            ->setEntityLabelInSingular('')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('person', 'Utilisateur')->autocomplete();

        yield TextField::new('reason', 'Motif');

        yield DateTimeField::new('createdAt', 'Inscription')->hideOnForm();
    }
}
