<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-04
 * Time: 14:38
 */

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstname', TextType::class, [
                    'label' => 'First Name'
                ])
                ->add('lastname', TextType::class, [
                    'label' => 'Last Name',
                ])
                ->add('dateofbirth', BirthdayType::class, [
                    'placeholder' => [

                        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    ],
                    'format' => 'dd-MM-yyyy',
                    'label' => 'Date of Birth'

                ])
                ->add('sexe', ChoiceType::class, [
                    'placeholder' => 'Select a gender',
                    'choices' =>  [
                        'Women' => 'Women',
                        'Man' => 'Man'

                    ],
                    'label' =>'Gender',

                 ])
                ->add('contacttype', ChoiceType::class, [
                    'label' => 'Contact Type',
                    'placeholder' => 'Select a type',
                    'choices' => [
                        'Employee' => 'Employee',
                        'Prospect' => 'Prospect',
                    ]
                ])
                ->add('socialesecunumber')
                ->add('mail', CollectionType::class, [
                        'entry_type' => MailType::class,
                        'allow_add' => true,
                        'prototype' => true,
                        'label' => false,
                        'by_reference' => false,
                        'entry_options' =>[
                            'label' => false
                        ]
                ])
               ->add('citizenshipdetails', CollectionType::class, [
                   'entry_type' => CityzenshipDetailsType::class,
                   'allow_add' =>true,
                   'prototype' => true,
                   'label' => false,
                   'by_reference' => false,
                   'entry_options' =>[
                       'label' => false
                   ]
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
                ->add('address', CollectionType::class,[
                    'entry_type'=> AddressType::class,
                    'allow_add' =>true,
                    'prototype' => true,
                    'label' => false,
                    'by_reference' => false,
                    'entry_options' =>[
                    'label' => false
                        ]
                ])
                ->add('contract', CollectionType::class,[
                    'entry_type'=> ContractType::class,
                    'allow_add' =>true,
                    'prototype' => true,
                    'label' => false,
                    'by_reference' => false,
                    'entry_options' =>[
                        'label' => false
                    ]
                ])
                ->add('bankdetails',CollectionType::class,[
                    'entry_type'=> BankDetailsType::class,
                    'allow_add' =>true,
                    'prototype' => true,
                    'label' => false,
                    'by_reference' => false,
                    'entry_options' =>[
                        'label' => false
                    ]
                ])
                ->add('Submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Contact::class
            ]
        );
    }

}