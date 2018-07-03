<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Context;
use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('summary', TextareaType::Class)
            ->add('description', TextareaType::Class)
            ->add('date', DateType::Class)
            ->add('roles', EntityType::class, array (
                'class'   => Role::class,
                'choice_label'    => 'value',
                'multiple' => true
            ))
            ->add('context', EntityType::class, array (
                'class'   => Context::class,
                'choice_label'    => 'value',
                'multiple' => false
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
        ));
    }
}