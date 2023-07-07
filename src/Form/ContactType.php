<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                    'placeholder' => 'Votre nom...',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                    'placeholder' => 'Votre email...',
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control outline my-3',
                    'placeholder' => 'Sujet de votre message...',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control outline my-3',
                    'placeholder' => 'Votre message ou commentaire...',
                ]
            ])
            ->add('conditions', CheckboxType::class, [
                'label' => 'En cochant cette case, vous acceptez que les informations saisies soient enregistrées dans le seul but de traiter votre demande.',
                'required' => true,
                'mapped' => false,
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-outline-dark btn-lg g-recaptcha',
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
