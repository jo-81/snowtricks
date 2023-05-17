<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TrickCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trick::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add('title')
            ->add('createdAt')
            ->add('author')
            ->add('published')
        ;

        /** @var User $user */
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $filters->add('valided');
        }

        return $filters;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(CRUD::PAGE_INDEX, 'Liste des figures')
            ->setEntityLabelInSingular('figure')
            ->setEntityLabelInPlural('figures')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Titre');
        yield SlugField::new('slug', 'Slug')->setTargetFieldName('title')->onlyOnDetail();
        yield DateTimeField::new('createdAt', 'Crée le')->hideOnForm();
        yield BooleanField::new('valided', 'Valider')->hideWhenCreating()->setPermission('ROLE_ADMIN');
        yield BooleanField::new('published', 'Publié');
        yield AssociationField::new('author', 'Auteur')->hideOnForm();
    }
}
