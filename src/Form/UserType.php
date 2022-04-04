<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'E-Mail',
                    'class' => 'form-control'
                ]
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Username',
                    'class' => 'form-control'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'label' => false,
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mot de passe saisis doivent correspondre',
                'attr' => [
                    'class' => 'form-control form-control-lg form-control-a'
                ],
                'first_options' => [
                    'label' => false,
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                ]

            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'attr' => [
                    'placeholder' => 'Rôle',
                    'class' => 'form-control'
                ]
            ])
        ;

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
