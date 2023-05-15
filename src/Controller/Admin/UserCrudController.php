<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Image\AvatarType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMIN%')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('username')
            ->add('email')
            ->add('createdAt')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_DETAIL, Action::INDEX)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->setPermission(CRUD::PAGE_DETAIL, 'USER_PROFILE')
            ->setPermission(CRUD::PAGE_INDEX, 'ROLE_ADMIN')
            ->setPermission(CRUD::PAGE_EDIT, 'USER_EDIT')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', 'Pseudo')->hideOnForm(),
            EmailField::new('email', 'Adresse email'),
            DateTimeField::new('createdAt', 'Inscription')->hideOnForm(),
            DateTimeField::new('editedAt', 'Dernière modification')->onlyOnDetail(),

            TextField::new('avatar')->setFormType(AvatarType::class)->onlyWhenUpdating()->setRequired(false),

            ImageField::new('avatar.path', 'Avatar')->setBasePath('/images/avatar/')->onlyOnDetail(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(CRUD::PAGE_DETAIL, 'Profil')
            ->setPageTitle(CRUD::PAGE_INDEX, 'Utilisateurs')
        ;
    }
}
