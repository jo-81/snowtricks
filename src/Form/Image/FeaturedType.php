<?php

namespace App\Form\Image;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FeaturedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('trickFile', VichImageType::class, [
                'attr' => ['required' => false],
                'label' => 'Image',
                // 'allow_delete' => false,
                // 'constraints' => [
                //     new ImageConstraint(
                //         minWidth: 650
                //     )
                // ]
            ])
            ->add('alt', TextType::class, [
                'label' => 'Text alternatif',
                'help' => 'Description de l\'image',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'upload_dir' => '/images/trick/',
            'upload_filename' => '/images/trick/',
        ]);
    }
}
