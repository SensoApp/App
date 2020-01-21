<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('contact', EntityType::class,[
                'class' => Contact::class,
                'placeholder' => 'Select a contact',
                'choice_label' => function(Contact $contact){
                    return $contact->getFirstname().' '.$contact->getLastname();
                },
                'required' => false

            ])
            ->add('email')
            ->add('roles', ChoiceType::class, [

                'choices' => [
                    'ROLES' => [
                        'Admin' => 'ROLE_ADMIN',
                        'User'  => 'ROLE_USER'
                    ]
                ],
                'multiple' => true,
                'label' => false
            ])
            ->add('Register', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
