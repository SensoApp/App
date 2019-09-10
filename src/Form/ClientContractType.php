<?php

namespace App\Form;

use App\Entity\ClientContract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClientContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientname')
            ->add('startDate')
            ->add('endDate')
            ->add('rate')
            ->add('extrapercentsatyrday', IntegerType::class, [

                'label' => 'Extra percentage for Saturdays',
                'attr' => ['placeholder' => 'Expecting numbers e.g. 150 for 150%'],
                'required' => false
            ])
            ->add('extrapercentsunday', IntegerType::class, [

                'label' => 'Extra percentage for Sundays',
                'attr' => ['placeholder' => 'Expecting numbers e.g. 200 for 200%'],
                'required' => false
            ])
            ->add('extrapercentbankholidays', IntegerType::class, [

                'label' => 'Extra percentage for Bank holidays',
                'attr' => ['placeholder' => 'Expecting numbers e.g. 160 for 160%'],
                'required' => false
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'by_reference' => false,
                'placeholder' => 'Select a user'
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientContract::class,
        ]);
    }
}
