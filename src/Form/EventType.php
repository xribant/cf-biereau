<?php

namespace App\Form;

use App\Entity\Event;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Nom de l\'évènement',
                        'class' => 'form-control'
                    ]
            ])
            ->add('description', CKEditorType::class, [
                'required' => true,
                'label' => false,
                'config' => [
                    'toolbar' => 'standard'
                ],
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'form-control'
                ]
            ])
            ->add('scheduled_at', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'Europe/Brussels',
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => 'Date de l\'évènement'
                ]
            ])
           ->add('imageFile', VichImageType::class, [
               'required' => false,
               'label' => false,
               'download_link' => false,
               'download_uri' => false,
               'delete_label' => 'Supprimer l\'image courante',
               'imagine_pattern' => 'event_image',
               'attr' => [
                   'class' => 'imageField'
               ]
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
