<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-11
 * Time: 12:47
 */

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contractType', TextType::class)
                ->add('startDate')
                ->add('endDate')
                ->add('probationPeriodEndDate');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class
        ]);
    }

}