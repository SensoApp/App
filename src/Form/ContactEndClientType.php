<?php

namespace App\Form;

use App\Entity\ContactEndClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactEndClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientname', TextType::class, [
                'label' => 'Client name'
            ])
            ->add('type')
            ->add('contactperson', TextType::class, [

                'label' => 'Contact person'
            ])
            ->add('phone', CollectionType::class, [
                'entry_type' => PhoneType::class,
                'allow_add' =>true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
                'entry_options' =>[
                    'label' => false
                ]
            ])
            ->add('mail', CollectionType::class, [
                'entry_type' => MailType::class,
                'allow_add' =>true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
                'entry_options' =>[
                    'label' => false
                ]
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactEndClient::class,
        ]);
    }
}
