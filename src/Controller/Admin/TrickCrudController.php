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
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
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
            ->add('category')
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
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->setPermission(Crud::PAGE_DETAIL, 'TRICK_SHOW')
            ->setPermission(Crud::PAGE_EDIT, 'TRICK_EDIT')
            ->setPermission(Action::DELETE, 'TRICK_DELETE')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Titre')->setColumns('col-12 col-sm-6 col-xl-3');

        yield SlugField::new('slug', 'Slug')->setTargetFieldName('title')->onlyOnDetail();

        yield DateTimeField::new('createdAt', 'Crée le')->hideOnForm();
        yield DateTimeField::new('editedAt', 'Modifié le')->hideOnForm();

        yield AssociationField::new('category', 'Catégorie')->setColumns('col-12 col-sm-6 col-xl-3');
        yield AssociationField::new('author', 'Auteur')->hideOnForm()->setPermission('ROLE_ADMIN');

        yield FormField::addRow();
        yield BooleanField::new('published', 'Publié')
            ->setColumns('col-12 col-sm-6 col-md-4 col-lg-3')
        ;
        yield BooleanField::new('valided', 'Valider')
            ->hideWhenCreating()
            ->setPermission('ROLE_ADMIN')
            ->setColumns('col-12 col-sm-6 col-md-4 col-lg-3')
        ;

        yield FormField::addPanel('Media')
            ->collapsible()
            ->onlyOnForms()
            ->setHelp('Vous devez ajouté au moins une image')
            ->setColumns('col-12 col-xl-6')
        ;

        yield CollectionField::new('images')
            ->setEntryType(FeaturedType::class)
            ->setHelp("Vous pouvez ajouté jusqu'à 4 images")
            ->setFormTypeOptions([
                'error_bubbling' => false,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->setColumns('col-12 col-sm-6')
            ->renderExpanded()
            ->setEntryIsComplex()
            ->onlyOnForms()
        ;

        yield CollectionField::new('videos')
            ->setEntryType(VideoType::class)
            ->onlyOnForms()
            ->setColumns('col-12 col-sm-6')
        ;

        yield ArrayField::new('images')
            ->onlyOnDetail()
        ;

        yield ArrayField::new('videos')
            ->onlyOnDetail()
        ;

        yield FormField::addPanel()->onlyOnForms();

        yield TextEditorField::new('content', 'Description')->onlyOnForms()->setColumns('col-12 col-xl-6');
        yield TextareaField::new('content', 'Description')
            ->onlyOnDetail()
            ->setColumns('col-12 col-xl-6')
            ->renderAsHtml()
            ->stripTags()
        ;
    }
}
