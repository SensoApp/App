<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use function PHPSTORM_META\type;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
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
            ->add('plainPassword', RepeatedType::class,[

                'type' => PasswordType::class,

                'first_options' => ['label' => 'password'],
                'second_options' => ['label' => 'Repeat password'],

                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
