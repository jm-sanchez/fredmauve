<?php

namespace App\Form;

use App\Entity\Work;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technique', TextType::class, [
                'label' => '(*) Technique',
                'required' => true,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('format', TextType::class, [
                'label' => '(*) Format',
                'required' => true,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => '(*) Categorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('title', TextType::class, [
                'label' => '(*) Titre',
                'required' => true,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('images', FileType::class, [
                'label' => '(*) Image(s)',
                'mapped' => false,
                'multiple' => true,
                'required' => true,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('date', IntegerType::class, [
                'label' => 'Année de création',
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix (€)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => '(*) Quantité',
                'required' => true,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
            ->add('saleable', CheckboxType::class, [
                'label' => 'Afficher dans la boutique',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input my-3',
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug (Titre dans le format suivant : titre-de-mon-œuvre)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }
}
