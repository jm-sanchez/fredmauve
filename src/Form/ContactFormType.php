<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline',
                    'placeholder' => 'Votre nom...',
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control outline',
                    'placeholder' => 'Votre email...',
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control outline',
                    'placeholder' => 'Sujet de votre message...',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control outline',
                    'placeholder' => 'Votre message ou commentaire...',
                ]
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-outline-light btn-lg'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
