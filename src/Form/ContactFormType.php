<?php

namespace App\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre nom')
                ],
                'label' => 'Votre nom:'
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre e-mail'),
                    new Email(message: 'Veuillez entrer une adresse e-mail valide')
                ],
                'label' => 'Adresse E-Mail:',
            ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez taper un message')
                ],
                'label' => 'Votre message:',
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'app_home'
            ])
        ;
    }
}
