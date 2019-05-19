<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-11
 * Time: 19:36
 */

namespace App\Form;


use App\Entity\BankDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('iban', TextType::class)
                ->add('biccode', TextType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>BankDetails::class
        ]);
    }

}