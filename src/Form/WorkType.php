<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Work;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technique')
            ->add('format')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('title')
            ->add('images', FileType::class, [
                'label' => false,
                'mapped' => false,
                'multiple' => true,
                // pour vérifier le contrôl du back (message flash)
                'required' => false,
            ])
            // ->add('image_detail')
            ->add('description')
            ->add('date')
            ->add('localisation')
            ->add('price')
            ->add('quantity')
            ->add('saleable')
            ->add('slug')
            // ->add('administrator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }
}
