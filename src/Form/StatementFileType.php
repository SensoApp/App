<?php

namespace App\Form;

use App\Entity\StatementFile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class  StatementFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operationdate', DateType::class, [
                'label' => 'Operation Date'
            ])
            ->add('operations', TextType::class, [
                'help' => 'enter an operation type'
            ])
            ->add('communication' , TextType::class, [
                'help' => 'enter a communication'
            ])
            ->add('account')
            ->add('amount')
            ->add('user', EntityType::class, [
                'placeholder' => 'Select a user',
                'class' => User::class,
                'choice_label' => function($choices){
                        return $choices->getFirstname().' '.$choices->getLastname();
                }
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StatementFile::class,
        ]);
    }
}

