<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('id', HiddenType::class)
                ->add('communication', TextareaType::class, [
                    'label' => false
                ])
                ->add('Submit', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-xs btn-primary']
                ]);

    }

}