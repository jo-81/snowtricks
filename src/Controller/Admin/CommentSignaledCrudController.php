<?php

namespace App\Controller\Admin;

use App\Entity\CommentSignaled;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Security("is_granted('ROLE_ADMIN')")]
class CommentSignaledCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommentSignaled::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('createdAt', 'Signalé le')->hideOnForm();
        yield DateTimeField::new('editedAt', 'Modifié le')->onlyOnDetail();
        yield TextField::new('reason', 'Raison');
        yield AssociationField::new('comment', 'Commentaire');
        yield BooleanField::new('valided', 'Bloqué ?')->hideWhenCreating();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(CRUD::PAGE_INDEX, 'Commentaires signalés')
            ->setPageTitle(CRUD::PAGE_DETAIL, 'Commentaire')
            ->setEntityLabelInSingular('commentaire')
            ->setEntityLabelInPlural('commentaires')
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('valided');
    }
}
