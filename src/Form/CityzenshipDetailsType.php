<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-11
 * Time: 19:21
 */

namespace App\Form;

use App\Entity\CitizenshipDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityzenshipDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('documentType', ChoiceType::class, [
            'placeholder' => 'Select a Type of document',
            'choices' =>  [
                'Passport' => 'Passport',
                'IdCard' => 'IdCard'

            ],
            'label' =>'Official Document Type',

        ])
                ->add('documentId', TextType::class)
                ->add('deliveryDate', DateType::class)
                ->add('expireDate', DateType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class' => CitizenshipDetails::class
        ]);
    }
}