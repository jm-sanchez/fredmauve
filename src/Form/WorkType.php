<?php

namespace App\Form;

use App\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technique')
            ->add('format')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Art' => 1,
                    'Illustration' => 2,
                ]
            ])
            ->add('title')
            ->add('image')
            ->add('image_detail')
            ->add('description')
            ->add('date')
            ->add('localisation')
            ->add('price')
            ->add('quantity')
            ->add('saleable')
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
