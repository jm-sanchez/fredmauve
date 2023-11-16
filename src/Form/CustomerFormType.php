<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse e-mail *',
                    'class' => 'input_form',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom *',
                    'class' => 'input_form',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom *',
                    'class' => 'input_form',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse *',
                    'class' => 'input_form',
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'N° de téléphone *',
                    'class' => 'input_form',
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal *',
                    'class' => 'input_form',
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville *',
                    'class' => 'input_form',
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'placeholder' => 'Sélectionner votre pays',
                'attr' => [
                    'class' => 'input_form'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Finaliser votre commande',
                'attr' => [
                    'class' => 'button_validate'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
