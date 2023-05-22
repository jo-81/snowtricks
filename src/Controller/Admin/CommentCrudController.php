<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        /** @var User $user */
        $user = $this->getUser();
        $query = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)->orderby('entity.id', 'DESC');

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $query;
        }

        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.author = :user')
            ->setParameter('user', $user->getId())
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextareaField::new('content', 'Commentaire')
            ->renderAsHtml()
            ->stripTags()
            ->hideOnForm()
        ;

        yield TextEditorField::new('content', 'Description')
            ->onlyWhenUpdating()
            ->setColumns('col-12 col-xl-6')
        ;

        yield DateTimeField::new('createdAt', 'Posté le')
            ->hideOnForm()
        ;

        yield DateTimeField::new('editedAt', 'Modifié le')
            ->onlyOnDetail()
            ->setPermission('ROLE_ADMIN')
        ;

        yield AssociationField::new('author', 'Auteur')
            ->hideOnForm()
            ->setPermission('ROLE_ADMIN')
        ;

        yield AssociationField::new('trick', 'Figure')
            ->hideOnForm()
            ->setPermission('ROLE_ADMIN')
        ;

        yield FormField::addPanel()->onlyOnDetail()->setPermission('ROLE_ADMIN');
        yield BooleanField::new('commentSignaled.valided', 'Bloqué ?')->onlyOnDetail()->setPermission('ROLE_ADMIN');
        yield TextField::new('commentSignaled.reason', 'Raison')->onlyOnDetail()->setPermission('ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        /** @var User $user */
        $user = $this->getUser();
        $titleIndex = in_array('ROLE_ADMIN', $user->getRoles()) ? 'Liste des commentaires' : 'Mes commentaires';

        return $crud
            ->setPageTitle(CRUD::PAGE_INDEX, $titleIndex)
            ->setPageTitle(CRUD::PAGE_DETAIL, 'Commentaire')
            ->setEntityLabelInSingular('commentaire')
            ->setEntityLabelInPlural('commentaires')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        /** @var User $user */
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $filters->add('author');
            $filters->add('trick');
        }

        return $filters;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->setPermission(Crud::PAGE_DETAIL, 'COMMENT_SHOW')
            ->setPermission(Crud::PAGE_EDIT, 'COMMENT_EDIT')

            ->setPermission(Action::DELETE, 'COMMENT_DELETE')
            ->setPermission(Action::EDIT, 'COMMENT_EDIT')
            ->setPermission(Action::DETAIL, 'COMMENT_SHOW')
        ;
    }
}
