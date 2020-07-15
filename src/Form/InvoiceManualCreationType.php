<?php


namespace App\Form;


use App\Entity\InvoiceCreationData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceManualCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('daysWorked', NumberType::class, [
                    'scale' => 1
                ])
                ->add('bankHolidays')
                ->add('workSaturdays')
                ->add('workSundays')
                ->add('user', EntityType::class, [
                    'class' => 'App\Entity\User',
                    'placeholder' => 'Select related user',
                    'choice_label' => function($choices){
                        return $choices->getFirstname().' '.$choices->getLastname();
                    }
                ])
                ->add('Create', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            'data_class' => InvoiceCreationData::class
        ]);
    }

}