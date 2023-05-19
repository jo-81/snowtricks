<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Entity\User;
use App\Form\Image\FeaturedType;
use App\Form\Video\VideoType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->setPermission(Crud::PAGE_DETAIL, 'TRICK_SHOW')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield BooleanField::new('published', 'Publié');

        yield AssociationField::new('author', 'Auteur')->hideOnForm()->setPermission('ROLE_ADMIN');

        yield TextField::new('title', 'Titre')->setColumns('col-12 col-sm-6 col-xl-4');

        yield SlugField::new('slug', 'Slug')->setTargetFieldName('title')->onlyOnDetail();

        yield DateTimeField::new('createdAt', 'Crée le')->hideOnForm();
        yield BooleanField::new('valided', 'Valider')->hideWhenCreating()->setPermission('ROLE_ADMIN');

        yield AssociationField::new('category', 'Catégorie')->hideOnIndex()->setColumns('col-12 col-sm-6 col-xl-4');

        yield FormField::addPanel('Media')
            ->collapsible()
            ->setHelp('Vous devez ajouté au moins une image')
            ->setColumns('col-12 col-xl-8')
        ;

        yield CollectionField::new('images')
            ->setEntryType(FeaturedType::class)
            ->onlyWhenCreating()
            ->setHelp('Vous pouvez ajouté jusqu\'a 3 images')
            ->setFormTypeOptions([
                'error_bubbling' => false,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->renderExpanded()
            ->setEntryIsComplex()
        ;

        yield CollectionField::new('videos')->setEntryType(VideoType::class)->onlyWhenCreating();

        yield FormField::addPanel();

        yield TextEditorField::new('content', 'Description')->hideOnIndex()->setColumns('col-12 col-xl-8');
    }
}
